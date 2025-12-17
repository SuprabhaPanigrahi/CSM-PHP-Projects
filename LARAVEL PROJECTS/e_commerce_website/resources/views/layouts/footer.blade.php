<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4 class="fw-bold mb-4">
                    <i class="bi bi-shop me-2"></i>ShopEasy
                </h4>
                <p>Your one-stop destination for quality products at affordable prices. We deliver happiness right to your doorstep.</p>
                <div class="social-icons mt-4">
                    <a href="#" class="text-white me-3">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="#" class="text-white me-3">
                        <i class="bi bi-twitter fs-5"></i>
                    </a>
                    <a href="#" class="text-white me-3">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="#" class="text-white">
                        <i class="bi bi-linkedin fs-5"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="fw-bold mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ route('products.index') }}" class="text-white text-decoration-none">Products</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Contact</a></li>
                    <li class="mb-2"><a href="{{ route('cart.index') }}" class="text-white text-decoration-none">Cart</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-4">Customer Service</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Shipping Policy</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Returns & Refunds</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Terms of Service</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 mb-4">
                <h5 class="fw-bold mb-4">Contact Info</h5>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-geo-alt me-2 mt-1"></i>
                        <span>123 Street, City, Country</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-telephone me-2"></i>
                        <span>+1 234 567 890</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-envelope me-2"></i>
                        <span>support@shopeasy.com</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-clock me-2"></i>
                        <span>Mon - Fri: 9:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
        
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <p class="mb-0">&copy; {{ date('Y') }} ShopEasy. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <img src="https://img.icons8.com/color/48/000000/visa.png" alt="Visa" class="me-2" style="height: 30px;">
                <img src="https://img.icons8.com/color/48/000000/mastercard.png" alt="Mastercard" class="me-2" style="height: 30px;">
                <img src="https://img.icons8.com/color/48/000000/paypal.png" alt="PayPal" style="height: 30px;">
            </div>
        </div>
    </div>
</footer>