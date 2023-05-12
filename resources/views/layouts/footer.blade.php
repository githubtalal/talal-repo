<footer id="footer" class="d-flex justfiy-content-center align-items-center">
    <div class="w-100">
        <div class="footer-background desk">
            <img class="img-fluid" src="Baseet/images/DesktopFooterBackground.svg" id="footer-desk-back">
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 ">
                <ul id="about">
                    <li><h3 class="big-src-text">تواصل معنا</h3></li>
                    <li><a>Info@ecart.sy</a></li>
                    <li><a href="tel:+963987209645">+963 987 209 645 </a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" id="footer-col">
                <ul id="social">
                    <li>
                        <a href="https://t.me/+963987209645">
                            <img class="img-fluid" src="Baseet/images/TelegramIcon5.png" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="https://facebook.com/ecart.sy">
                            <img class="img-fluid" src="Baseet/images/FacebookIcon.svg" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/ecart.sy">
                            <img class="img-fluid" src="Baseet/images/InstagramIcon.svg" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="https://wa.me/+963987209645">
                            <img class="img-fluid" src="Baseet/images/WhatsappIcon.png" alt="">
                        </a>
                    </li>
                </ul>
                <h3 id="rights">جميع الحقوق محفوظة لشركة الثعلب الماكر</h3>
                <h3 id="rights">بالتعاون مع فاتورة</h3>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                <ul id="about">
                    <li><h3 class="big-src-text">عن إي كارت</h3></li>
                    <li><a href="{{route('tos')}}">اتفاقية الاستخدام</a></li>
                    <li><a href="{{route('privacy')}}">سياسة الخصوصية</a></li>
                    <li><a href="{{route('whoWeAre')}}">من نحن</a></li>
                </ul>
            </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mob-flex mob">
                    <ul id="social">
                        <li>
                            <a href="https://t.me/+963987209645">
                                <img class="img-fluid" src="Baseet/images/TelegramIcon5.png" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="https://facebook.com/ecart.sy">
                                <img class="img-fluid" src="Baseet/images/FacebookIcon.svg" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/ecart.sy">
                                <img class="img-fluid" src="Baseet/images/InstagramIcon.svg" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/+963987209645">
                                <img class="img-fluid" src="Baseet/images/WhatsappIcon.png" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 mob ord-3">
                    <h3 id="rights">جميع الحقوق محفوظة لشركة الثعلب الماكر</h3>
                    <h3 id="rights">بالتعاون مع فاتورة</h3>
                </div>
        </div>
    </div>
</footer>
@yield('footer')
