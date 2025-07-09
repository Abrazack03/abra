// Main JavaScript for Beipoa eCommerce Website
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initHeroSlider();
    initMobileMenu();
    initSmoothScrolling();
    loadCategories();
    loadFeaturedProducts();
    loadMostLovedProducts();
    initSearch();
    initWishlist();
    initCart();
});

// Hero Slider Functionality
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator');
    let currentSlide = 0;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Auto-slide every 5 seconds
    setInterval(nextSlide, 5000);
    
    // Click indicators to change slides
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
}

// Mobile Menu Toggle
function initMobileMenu() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }
}

// Smooth Scrolling for Shop Now Button
function initSmoothScrolling() {
    const shopNowButtons = document.querySelectorAll('.shop-now-btn');
    
    shopNowButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = button.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Load Categories Dynamically
async function loadCategories() {
    try {
        const response = await fetch('includes/get_categories.php');
        const categories = await response.json();
        
        const categoriesGrid = document.getElementById('categoriesGrid');
        if (categoriesGrid) {
            categoriesGrid.innerHTML = categories.map(category => `
                <div class="category-card" onclick="window.location.href='categories.php?id=${category.id}'">
                    <img src="${category.image || 'assets/images/categories/default.jpg'}" alt="${category.name}">
                    <div class="category-info">
                        <h3>${category.name}</h3>
                        <p>${category.product_count} Products</p>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        // Show default categories if loading fails
        showDefaultCategories();
    }
}

// Show Default Categories (fallback)
function showDefaultCategories() {
    const categoriesGrid = document.getElementById('categoriesGrid');
    if (categoriesGrid) {
        const defaultCategories = [
            { id: 1, name: 'Mobile Phones', image: 'assets/images/categories/phones.jpg', count: 150 },
            { id: 2, name: 'Computers', image: 'assets/images/categories/computers.jpg', count: 89 },
            { id: 3, name: 'Electronics', image: 'assets/images/categories/electronics.jpg', count: 234 },
            { id: 4, name: 'TVs', image: 'assets/images/categories/tvs.jpg', count: 67 },
            { id: 5, name: 'Sports', image: 'assets/images/categories/sports.jpg', count: 123 },
            { id: 6, name: 'Fashion', image: 'assets/images/categories/fashion.jpg', count: 189 }
        ];
        
        categoriesGrid.innerHTML = defaultCategories.map(category => `
            <div class="category-card" onclick="window.location.href='categories.php?id=${category.id}'">
                <img src="${category.image}" alt="${category.name}" onerror="this.src='assets/images/placeholder.jpg'">
                <div class="category-info">
                    <h3>${category.name}</h3>
                    <p>${category.count} Products</p>
                </div>
            </div>
        `).join('');
    }
}

// Load Featured Products
async function loadFeaturedProducts() {
    try {
        const response = await fetch('includes/get_featured_products.php');
        const products = await response.json();
        
        const productsGrid = document.getElementById('featuredProductsGrid');
        if (productsGrid) {
            productsGrid.innerHTML = products.map(product => createProductCard(product)).join('');
        }
    } catch (error) {
        console.error('Error loading featured products:', error);
        showDefaultProducts('featuredProductsGrid');
    }
}

// Load Most Loved Products
async function loadMostLovedProducts() {
    try {
        const response = await fetch('includes/get_most_loved_products.php');
        const products = await response.json();
        
        const carousel = document.getElementById('mostLovedCarousel');
        if (carousel) {
            carousel.innerHTML = products.map(product => createProductCard(product)).join('');
        }
    } catch (error) {
        console.error('Error loading most loved products:', error);
        showDefaultProducts('mostLovedCarousel');
    }
}

// Create Product Card HTML
function createProductCard(product) {
    const rating = generateStars(product.rating || 4.5);
    const label = product.is_hot ? 'HOT' : (product.is_sale ? 'SALE' : '');
    const labelClass = product.is_hot ? 'label-hot' : 'label-sale';
    
    return `
        <div class="product-card">
            <div class="product-image">
                <img src="${product.image || 'assets/images/products/default.jpg'}" 
                     alt="${product.name}" 
                     onerror="this.src='assets/images/placeholder.jpg'">
                ${label ? `<span class="product-label ${labelClass}">${label}</span>` : ''}
                <button class="wishlist-btn" onclick="toggleWishlist(${product.id})">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <div class="product-rating">
                    <div class="stars">${rating}</div>
                    <span class="rating-text">(${product.reviews || 0} reviews)</span>
                </div>
                <div class="product-price">TSh ${formatPrice(product.price)}</div>
                <button class="add-to-cart-btn" onclick="addToCart(${product.id}, '${product.name}', ${product.price})">
                    Add to Cart
                </button>
            </div>
        </div>
    `;
}

// Generate Star Rating
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    let stars = '';
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star star"></i>';
    }
    
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt star"></i>';
    }
    
    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star star"></i>';
    }
    
    return stars;
}

// Format Price with Commas
function formatPrice(price) {
    return new Intl.NumberFormat('en-TZ').format(price);
}

// Show Default Products (fallback)
function showDefaultProducts(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
        const defaultProducts = [
            { id: 1, name: 'iPhone 14 Pro Max', price: 2500000, rating: 4.8, reviews: 125, is_hot: true, image: 'assets/images/products/iphone.jpg' },
            { id: 2, name: 'Samsung Galaxy S23', price: 1800000, rating: 4.6, reviews: 89, is_sale: true, image: 'assets/images/products/samsung.jpg' },
            { id: 3, name: 'MacBook Pro M2', price: 4200000, rating: 4.9, reviews: 67, image: 'assets/images/products/macbook.jpg' },
            { id: 4, name: 'Sony WH-1000XM4', price: 450000, rating: 4.7, reviews: 234, image: 'assets/images/products/headphones.jpg' },
            { id: 5, name: 'iPad Air', price: 1200000, rating: 4.5, reviews: 156, image: 'assets/images/products/ipad.jpg' },
            { id: 6, name: 'Apple Watch Series 8', price: 800000, rating: 4.6, reviews: 98, image: 'assets/images/products/watch.jpg' },
            { id: 7, name: 'Dell XPS 13', price: 2800000, rating: 4.4, reviews: 78, image: 'assets/images/products/laptop.jpg' },
            { id: 8, name: 'AirPods Pro', price: 350000, rating: 4.8, reviews: 189, is_hot: true, image: 'assets/images/products/airpods.jpg' }
        ];
        
        container.innerHTML = defaultProducts.map(product => createProductCard(product)).join('');
    }
}

// Search Functionality
function initSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    window.location.href = `search.php?q=${encodeURIComponent(searchTerm)}`;
                }
            }
        });
        
        // Search icon click
        const searchIcon = document.querySelector('.search-bar i');
        if (searchIcon) {
            searchIcon.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    window.location.href = `search.php?q=${encodeURIComponent(searchTerm)}`;
                }
            });
        }
    }
}

// Wishlist Functionality
let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');

function toggleWishlist(productId) {
    const index = wishlist.indexOf(productId);
    if (index > -1) {
        wishlist.splice(index, 1);
    } else {
        wishlist.push(productId);
    }
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    updateWishlistUI();
    
    // Show feedback
    showNotification(index > -1 ? 'Removed from wishlist' : 'Added to wishlist');
}

function updateWishlistUI() {
    // Update wishlist button states
    document.querySelectorAll('.wishlist-btn').forEach((btn, index) => {
        const productId = parseInt(btn.getAttribute('onclick').match(/\d+/)[0]);
        if (wishlist.includes(productId)) {
            btn.style.background = '#ef4444';
            btn.style.color = 'white';
        } else {
            btn.style.background = 'rgba(255,255,255,0.9)';
            btn.style.color = '#333';
        }
    });
}

// Cart Functionality
let cart = JSON.parse(localStorage.getItem('cart') || '[]');

function addToCart(productId, productName, price) {
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification(`${productName} added to cart!`);
}

function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
        
        // Add animation
        cartCount.style.transform = 'scale(1.2)';
        setTimeout(() => {
            cartCount.style.transform = 'scale(1)';
        }, 200);
    }
}

function initCart() {
    updateCartCount();
    updateWishlistUI();
    
    // Cart icon click
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            window.location.href = 'cart.php';
        });
    }
}

// Notification System
function showNotification(message, type = 'success') {
    // Remove existing notification
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add notification styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        animation: slideInRight 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add notification animations to CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Lazy Loading for Images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Currency Formatter for Tanzanian Shilling
function formatCurrency(amount) {
    return `TSh ${new Intl.NumberFormat('en-TZ').format(amount)}`;
}

// Initialize lazy loading when DOM is ready
document.addEventListener('DOMContentLoaded', initLazyLoading);