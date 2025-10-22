<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Đăng bài') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Khu vực thống kê -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Tổng số bài đăng</h3>
                    <p class="text-3xl mt-2">{{ $totalPosts }}</p>
                </div>
                <div class="bg-green-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Tổng số lượt tương tác</h3>
                    <p class="text-3xl mt-2">{{ $totalViews }}</p>
                </div>
                <div class="bg-yellow-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Bài đăng trong tuần</h3>
                    <p class="text-3xl mt-2">{{ $postsThisWeek }}</p>
                </div>
                <div class="bg-red-600 p-4 rounded-lg text-white shadow">
                    <h3 class="text-lg font-semibold">Bài đăng trong tháng</h3>
                    <p class="text-3xl mt-2">{{ $postsThisMonth }}</p>
                </div>
            </div>

            <!-- Nút chọn khoảng thời gian -->
            <div class="flex justify-center mb-6">
                <button class="toggle-btn active" data-period="day">Ngày</button>
                <button class="toggle-btn" data-period="week">Tuần</button>
                <button class="toggle-btn" data-period="month">Tháng</button>
            </div>

            <!-- Khu vực biểu đồ -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <canvas id="postsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- CSS cho nút chọn -->
<style>
    .toggle-btn {
        background-color: #555;
        color: #fff;
        padding: 10px 20px;
        margin: 0 5px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .toggle-btn.active {
        background-color: #007bff;
    }
</style>
<!-- Thêm CDN Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('postsChart').getContext('2d');

        // Dữ liệu ban đầu (theo ngày)
        let chartData = {
            labels: @json($dailyLabels),
            datasets: [{
                label: 'Số bài đăng',
                data: @json($dailyData),
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        };

        const postsChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Hàm cập nhật dữ liệu cho biểu đồ
        function updateChart(period) {
            let labels = [];
            let data = [];

            switch (period) {
                case 'day':
                    labels = @json($dailyLabels);
                    data = @json($dailyData);
                    break;
                case 'week':
                    labels = @json($weeklyLabels);
                    data = @json($weeklyData);
                    break;
                case 'month':
                    labels = @json($monthlyLabels);
                    data = @json($monthlyData);
                    break;
            }

            postsChart.data.labels = labels;
            postsChart.data.datasets[0].data = data;
            postsChart.update();
        }

        // Bắt sự kiện khi bấm vào nút
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateChart(this.getAttribute('data-period'));
            });
        });
    });
</script>
