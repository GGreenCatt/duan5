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
            {{ __('Dashboard') }} <!-- Sửa thành Dashboard cho phù hợp -->
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4"> <!-- Thêm px-4 cho padding mobile -->
            <!-- Ô hiển thị thời gian thực -->
            <div class="flex justify-center mb-8" style="margin-top:-25px ">
                <div id="realtime-clock" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-lg font-bold tracking-widest text-center"> <!-- Thêm text-center -->
                </div>
            </div>

            <!-- Khu vực thống kê -->
            <!-- Sử dụng class stats-container để wrap các card lại -->
            <div class="stats-container mb-8">
                <div class="stat-card"> <!-- Bỏ class Tailwind: flex-1 min-w-[200px] vì đã xử lý trong CSS -->
                    <h3 class="text-lg font-semibold">Tổng số bài đăng</h3>
                    <p class="text-3xl mt-2 font-bold">{{ $totalPosts }}</p>
                </div>

                <div class="stat-card">
                    <h3 class="text-lg font-semibold">Bài đăng trong tuần</h3>
                    <p class="text-3xl mt-2 font-bold">{{ $postsThisWeek }}</p>
                </div>

                <div class="stat-card">
                    <h3 class="text-lg font-semibold">Bài đăng trong tháng</h3>
                    <p class="text-3xl mt-2 font-bold">{{ $postsThisMonth }}</p>
                </div>
            </div>

            <!-- Phần 2: Biểu đồ -->
            <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow mb-8"> <!-- p-4 cho mobile, sm:p-6 cho lớn hơn -->
                <!-- Sử dụng class chart-header -->
                <div class="chart-header">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2 sm:mb-0">Biểu đồ số bài đăng</h3> <!-- mb-2 sm:mb-0 để có khoảng cách trên mobile -->
                    <select id="chartMode" class="text-black px-4 py-2 rounded"> <!-- Bỏ class Tailwind vì đã xử lý trong CSS -->
                        <option value="day">Ngày</option>
                        <option value="week">Tuần</option>
                        <option value="month" selected>Tháng</option>
                    </select>
                </div>
                <div style="height: 300px; sm:height: 400px; max-width: 100%;"> <!-- height nhỏ hơn cho mobile -->
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

     .stat-card {
        padding: 1.5rem;
        border-radius: 1rem;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        /* Mặc định chiếm toàn bộ chiều rộng trên màn hình nhỏ */
        width: 100%;
        margin-bottom: 1rem; /* Thêm khoảng cách dưới khi xếp chồng */
    }

    .stat-card:nth-child(1) {
        background: linear-gradient(135deg, #6366F1, #EC4899);
    }

    .stat-card:nth-child(2) {
        background: linear-gradient(135deg, #10B981, #3B82F6);
    }

    .stat-card:nth-child(3) {
        background: linear-gradient(135deg, #F97316, #EF4444);
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
        background-color: #f3f4f6;
        color: #000;
        border: 1px solid #ddd;
        width: 100%; /* Chiếm toàn bộ chiều rộng trên mobile */
        max-width: 100%;
        transition: background-color 0.3s;
        margin-top: 0.5rem; /* Thêm chút khoảng cách với tiêu đề trên mobile */
    }

    #chartMode:hover {
        background-color: #e2e8f0;
    }

    /* --- Responsive Adjustments --- */

    /* Small screens and up (sm: 640px) - Bố cục như cũ cho desktop và tablet lớn */
    @media (min-width: 640px) {
        .stat-card {
            /* flex-1 sẽ được áp dụng lại từ class Tailwind */
            min-width: 350px; /* Giữ lại min-width cho màn hình lớn hơn */
            width: auto; /* Để flexbox quyết định chiều rộng */
            margin-bottom: 0; /* Bỏ margin-bottom khi nằm ngang */
        }
        #chartMode {
            width: 250px; /* Giới hạn lại chiều rộng cho desktop */
            margin-top: 0; /* Bỏ margin top */
        }
        /* Đảm bảo flex container cho stat cards chỉ áp dụng từ sm trở lên */
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            gap: 1.5rem; /* tương đương gap-6 của Tailwind */
        }
        /* Căn chỉnh dropdown chartMode và tiêu đề trên màn hình lớn */
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem; /* mb-4 */
        }
    }

     /* CSS cho ô hiển thị thời gian thực */
    #realtime-clock {
        font-size: 1rem; /* Kích thước chữ nhỏ hơn trên mobile */
        padding: 0.75rem 1.5rem; /* padding nhỏ hơn */
    }
    @media (min-width: 640px) { /* sm breakpoint */
        #realtime-clock {
            font-size: 1.5rem; /* text-2xl */
            padding: 1rem 2rem; /* px-8 py-4 */
        }
    }
    </style>
