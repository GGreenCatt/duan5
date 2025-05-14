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
                    <!-- Thời gian sẽ được cập nhật ở đây -->
                </div>
            </div>

            <!-- Phần 1: Khu vực thống kê -->
            <div class="flex flex-wrap justify-between gap-6 mb-8" style="margin-bottom: 2.5rem;">
                <div class="stat-card bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 border-blue-300 flex-1 min-w-[340px] shadow-lg" style="background-color: #6366f1;">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M4 8h16M4 13h16M4 18h16" />
                        </svg>
                        <h3 class="text-lg font-semibold">Tổng số bài đăng</h3>
                    </div>
                    <p class="text-3xl mt-2 font-bold">{{ $totalPosts }}</p>
                </div>

                <div class="stat-card bg-gradient-to-r from-green-400 via-cyan-400 to-blue-500 border-green-300 flex-1 min-w-[340px] shadow-lg" style="background-color: #22d3ee;">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="text-lg font-semibold">Bài đăng trong tuần</h3>
                    </div>
                    <p class="text-3xl mt-2 font-bold">{{ $postsThisWeek }}</p>
                </div>

                <div class="stat-card bg-gradient-to-r from-orange-400 via-pink-500 to-red-500 border-red-300 flex-1 min-w-[340px] shadow-lg" style="background-color: #fb7185;">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z" />
                        </svg>
                        <h3 class="text-lg font-semibold">Bài đăng trong tháng</h3>
                    </div>
                    <p class="text-3xl mt-2 font-bold">{{ $postsThisMonth }}</p>
                </div>
            </div>

            <!-- Phần 2: Biểu đồ -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Biểu đồ số bài đăng</h3>
                <div style="height: 400px; max-width: 100%;">
                    <canvas id="postsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS tùy chỉnh cho hiệu ứng hover -->
    <style>
        .stat-card {
            padding: 1.5rem;
            border-radius: 1rem;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            color: #ffffff;
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
            opacity: 0.5;
        }
    </style>
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
                const formatted = now.toLocaleDateString('vi-VN', options).replace(',', ' -');
                document.getElementById('realtime-clock').textContent = formatted;
            }
            updateClock();
            setInterval(updateClock, 1000);

            // Chart.js
            const ctx = document.getElementById('postsChart').getContext('2d');
            const labels = @json($months);
            const monthlyData = @json($monthlyData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số bài đăng mỗi tháng',
                        data: monthlyData,
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
                        x: {
                            ticks: { color: '#fff' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#fff' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: { color: '#fff' }
                        },
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
        });
    </script>
