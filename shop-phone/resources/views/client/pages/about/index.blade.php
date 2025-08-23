@extends('client.layout')

@section('content')
    <main>
        <div class="about">
            <!-- Breadcrumb -->
            {{-- <div class="d-none d-xxl-block mp-5 mb-5 container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('client.home') }}" class="text-stnt">Trang chủ</a>
                        </li>
                        <!-- <li class="breadcrumb-item"><a href="product.html" class="text-stnt">Sản phẩm</a></li> -->
                        <li class="breadcrumb-item active" aria-current="page">
                            Giới thiệu
                        </li>
                    </ol>
                </nav>
            </div> --}}
            <section class="banner position-relative">
                <div class="text-center" style="background-color: #3d3733;">
                    <p class="py-3 text-white mb-0">GIẢM GIÁ 20% cho đơn hàng đầu tiên. Đăng ký nhận thông báo
                        của chúng tôi!
                        <a href="" class="text-white fw-bold text-decoration-none"><span>Đăng
                                ký</span> &rarr;</a>
                    </p>
                </div>
                <img src="https://marketplace.canva.com/EAGc4dL_rgk/1/0/1600w/canva-grey-and-red-simple-fashion-sale-banner-9EPx6fu8jqY.jpg"
                    class="w-100 h-75" alt="Thời trang thịnh hành">
                {{-- <label for="" class="fw-bold" style="filter: drop-shadow(0px 5px 15px black)">Mang đến phong cách thời
                    trang
                    đẳng cấp cho mọi người.</label> --}}
            </section>
            <section class="about-content">
                <div class="container about-item1">
                    <h1 class="text-center fw-bold my-4">Giới thiệu về chúng tôi</h1>
                    <div class="my-lg-5 my-md-5 my-sm-2">
                        <p>
                            Chúng tôi là thương hiệu thời trang hàng đầu với hơn 50 cửa hàng trên toàn quốc. Với đội ngũ
                            thiết kế tài năng và nhân viên tận tâm, chúng tôi cam kết mang đến những sản phẩm thời trang
                            chất lượng cao, phong cách đa dạng phù hợp với mọi lứa tuổi và sở thích.
                        </p>
                        <p>
                            Các bộ sưu tập của chúng tôi được lấy cảm hứng từ xu hướng thời trang quốc tế, kết hợp với nét
                            đẹp văn hóa Việt Nam. Mỗi sản phẩm đều được chăm chút từ khâu thiết kế đến sản xuất, đảm bảo
                            chất lượng và tính thẩm mỹ cao nhất.
                        </p>
                        <p>
                            Với hệ thống cửa hàng trực tiếp và nền tảng thương mại điện tử hiện đại, chúng tôi luôn sẵn sàng
                            phục vụ khách hàng mọi lúc, mọi nơi. Chúng tôi cũng thường xuyên tương tác với khách hàng thông
                            qua mạng xã hội, các chương trình khuyến mãi và dịch vụ chăm sóc khách hàng chuyên nghiệp.
                        </p>
                    </div>
                </div>
                <div class="container about-item2 w-75">
                    <h2 class="text-center fw-bold my-3">Giá trị cốt lõi của chúng tôi</h2>
                    <div class="row text-center my-lg-5 my-md-5 my-sm-2">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <figure>
                                <blockquote class="blockquote">
                                    <p class="fw-medium">THIẾT KẾ</p>
                                </blockquote>
                                <figcaption class="blockquote-footer mt-3">
                                    <cite title="Source Title">Sáng tạo không ngừng, mang đến những thiết kế độc đáo và thời
                                        thượng.</cite>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <figure>
                                <blockquote class="blockquote">
                                    <p class="fw-medium">CHẤT LƯỢNG</p>
                                </blockquote>
                                <figcaption class="blockquote-footer mt-3">
                                    <cite title="Source Title">Sử dụng chất liệu cao cấp, đảm bảo độ bền và sự thoải mái khi
                                        mặc.</cite>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <figure>
                                <blockquote class="blockquote">
                                    <p class="fw-medium">BỀN VỮNG</p>
                                </blockquote>
                                <figcaption class="blockquote-footer mt-3">
                                    <cite title="Source Title">Cam kết sản xuất thân thiện với môi trường và có trách nhiệm
                                        với xã hội.</cite>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <figure>
                                <blockquote class="blockquote">
                                    <p class="fw-medium">DỊCH VỤ</p>
                                </blockquote>
                                <figcaption class="blockquote-footer mt-3">
                                    <cite title="Source Title">Đội ngũ tư vấn chuyên nghiệp, sẵn sàng hỗ trợ khách hàng mọi
                                        lúc.</cite>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
                {{-- <div class="about-item3 overflow-hidden">
                    <div class="text-center bg-black" style="background-color: #3d3733 !important;">
                        <p class="py-4 text-white fs-4 mb-0">Chúng tôi tự hào về từng sản phẩm</p>
                    </div>
                    <div class="row">
                        <img class="w-25" src=".\client_asset\image\banner\fashion_about1.png" alt="Thời trang nam">
                        <img class="w-25" src=".\client_asset\image\banner\fashion_about2.png" alt="Thời trang nữ">
                        <img class="w-25" src=".\client_asset\image\banner\fashion_about3.png" alt="Thời trang trẻ em">
                        <img class="w-25" src=".\client_asset\image\banner\fashion_about4.png" alt="Phụ kiện">
                    </div>
                    <div class="text-center bg-black" style="background-color: #3d3733 !important;">
                        <p class="py-4 text-white fs-4 mb-0">... từ phòng thiết kế đến cửa hàng</p>
                    </div>
                </div> --}}
            </section>
        </div>
    </main>
@endsection