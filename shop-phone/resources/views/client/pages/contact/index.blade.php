@extends('client.layout')

@section('content')
<div class="section-box">
    <div class="breadcrumbs-div">
    <div class="container">
      <ul class="breadcrumb">
      <li><a class="font-xs color-gray-1000" href="{{ route('client.home') }}">Trang chủ</a></li>
      <li><a class="font-xs color-gray-500" href="#">Hỗ trợ</a></li>

      </ul>
    </div>
    </div>
  </div>
    <section>
        <div class="contact container">
            <div class="row all d-flex justify-content-center align-items-center">
                <div class="content-left col-6">
                    <div class="layout mt-5 mb-5 bg-light site_map">
                        {!! getSetting()->site_map !!}
                    </div>
                </div>
                <div class="form-container content-right col-6 d-flex justify-content-center align-items-center">
                    <div class="content-right">
                        <h2>Liên hệ với chúng tôi hôm nay</h2>
                        <p>Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn với mọi thắc mắc. Hãy liên hệ ngay để nhận được
                            những
                            tư vấn tốt nhất từ đội ngũ chuyên gia của chúng tôi!</p>
                        <form action="{{ route('client.contact.send') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-outline">
                                        <label class="form-label" for="form6Example1">Họ tên</label>
                                        <input type="text" name="name" id="form6Example1"
                                            class="form-control custom-form-control" placeholder="Họ tên" />
                                        @error('name')
                                            <small class="error text-danger">*{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-outline">
                                        <label class="form-label" for="form6Example2">Email</label>
                                        <input type="text" name="email" id="form6Example2"
                                            class="form-control custom-form-control" placeholder="Email" />
                                        @error('email')
                                            <small class="error text-danger">*{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-outline mb-4">
                                <label class="form-label" for="form6Example5">Để lại lời nhắn</label>
                                <textarea class="form-control" id="form6Example5" name="message" rows="4" placeholder="Nhập lời nhắn"></textarea>
                                @error('message')
                                    <small class="error text-danger">*{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-tgnt">Gửi lời nhắn</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .site_map iframe {
            width: 100%;
        }
    </style>
@endsection
