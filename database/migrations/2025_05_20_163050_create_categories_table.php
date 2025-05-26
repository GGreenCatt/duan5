<?php

// database/migrations/xxxx_xx_xx_create_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // Tên danh mục
            $table->unsignedBigInteger('parent_id')->nullable(); // Cha (null nếu là danh mục cha)
            $table->timestamps();

            // Khóa ngoại tham chiếu tới chính bảng categories để tạo cha-con
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
