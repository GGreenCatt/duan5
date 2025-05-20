@if (session('success') === 'Đăng nhập thành công')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Đăng nhập thành công!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    </script>
@endif
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Danh sách bài đăng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Ô hiển thị thời gian thực -->
            <div class="flex justify-center mb-8" style="margin-top:-25px ">
                <div id="realtime-clock" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-4 rounded-xl shadow-lg text-2xl font-bold tracking-widest">
                </div>
            </div>

            <!-- Khu vực thống kê -->
            <div class="flex flex-wrap justify-between gap-6 mb-8">
                <div class="stat-card bg-gradient-to-r from-indigo-500 to-pink-500 text-white flex-1 min-w-[200px] p-4 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold">Tổng số bài đăng</h3>
                    <p class="text-3xl mt-2 font-bold">{{ $totalPosts }}</p>
                </div>

                <div class="stat-card bg-gradient-to-r from-green-500 to-blue-500 text-white flex-1 min-w-[200px] p-4 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold">Bài đăng trong tuần</h3>
                    <p class="text-3xl mt-2 font-bold">{{ $postsThisWeek }}</p>
                </div>

                <div class="stat-card bg-gradient-to-r from-orange-500 to-red-500 text-white flex-1 min-w-[200px] p-4 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold">Bài đăng trong tháng</h3>
                    <p class="text-3xl mt-2 font-bold">{{ $postsThisMonth }}</p>
                </div>
            </div>

            <!-- Phần 2: Biểu đồ -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Biểu đồ số bài đăng</h3>
                    <select id="chartMode" class="bg-gray-200 text-black px-4 py-2 rounded w-1/3 max-w-xs">
                        <option value="day">Ngày</option>
                        <option value="week">Tuần</option>
                        <option value="month" selected>Tháng</option>
                    </select>
                </div>
                <div style="height: 400px; max-width: 100%;">
                    <canvas id="postsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Biểu đồ Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hiển thị thời gian thực
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long', year: 'numeric', month: '2-digit', day: '2-digit',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            };
            document.getElementById('realtime-clock').textContent = now.toLocaleDateString('vi-VN', options).replace(',', ' -');
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Chart.js
        const ctx = document.getElementById('postsChart').getContext('2d');
        const chartModeSelect = document.getElementById('chartMode');

        let chartData = {
            labels: @json($months),
            data: @json($monthlyData)
        };

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Số bài đăng',
                    data: chartData.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    hoverBackgroundColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#fff' } },
                    y: { beginAtZero: true, ticks: { color: '#fff' } }
                },
                plugins: {
                    legend: { labels: { color: '#fff' } },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return ' ' + tooltipItem.raw + ' bài đăng';
                            }
                        }
                    }
                }
            }
        });

        // Cập nhật biểu đồ khi chọn chế độ
        chartModeSelect.addEventListener('change', function() {
            updateChartMode(this.value);
        });

        function updateChartMode(mode) {
            if (mode === 'day') {
                chartData.labels = @json($dailyLabels);
                chartData.data = @json($dailyData);
                chart.data.datasets[0].label = 'Số bài đăng mỗi ngày';
            } else if (mode === 'week') {
                chartData.labels = @json($weeklyLabels);
                chartData.data = @json($weeklyData);
                chart.data.datasets[0].label = 'Số bài đăng mỗi tuần';
            } else {
                chartData.labels = @json($months);
                chartData.data = @json($monthlyData);
                chart.data.datasets[0].label = 'Số bài đăng mỗi tháng';
            }

            // Cập nhật lại biểu đồ
            chart.data.labels = chartData.labels;
            chart.data.datasets[0].data = chartData.data;
            chart.update();
        }
    });
</script>

    <!-- CSS tùy chỉnh cho hiệu ứng hover -->
    <style>
            .stat-card {
        padding: 1.5rem;
        border-radius: 1rem;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        color: #ffffff; /* Đảm bảo màu chữ trắng */
        background: linear-gradient(135deg, rgba(54, 162, 235, 0.8), rgba(75, 192, 192, 0.8));
    }

    .stat-card:nth-child(1) {
        background: linear-gradient(135deg, #6366F1, #EC4899); /* Màu nền thống kê 1 */
    }

    .stat-card:nth-child(2) {
        background: linear-gradient(135deg, #10B981, #3B82F6); /* Màu nền thống kê 2 */
    }

    .stat-card:nth-child(3) {
        background: linear-gradient(135deg, #F97316, #EF4444); /* Màu nền thống kê 3 */
    }

    .stat-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.15);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.2);
    }

    .stat-card:hover::after {
        opacity: 0.3;
    }
    #chartMode {
        background-color: #f3f4f6; /* Màu nền sáng */
        color: #000; /* Màu chữ đen */
        border: 1px solid #ddd; /* Đường viền nhẹ */
        width: 250px; /* Đảm bảo chiều rộng tương tự với tiêu đề */
        max-width: 100%; /* Đáp ứng với kích thước màn hình nhỏ */
        transition: background-color 0.3s;
    }

    #chartMode:hover {
        background-color: #e2e8f0; /* Đổi màu nền khi hover */
    }
    </style>
