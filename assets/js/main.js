/**
 * Balaji Kitchenware - Frontend Interactive JavaScript
 * Modern UI Animations, Multi-Photo Modal, Dynamic Filters & Search
 */

document.addEventListener('DOMContentLoaded', () => {

    // Navbar Scroll Effect
    const navbar = document.querySelector('.navbar-custom');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // Scroll Reveal Observer (Framer-motion feel)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
        revealObserver.observe(el);
    });

    // Product Category Filter & Live Search
    const searchInput = document.getElementById('productSearchInput');
    const categoryBtns = document.querySelectorAll('.cat-filter-btn');
    const productCards = document.querySelectorAll('.product-item-col');

    let activeCategory = 'all';

    function filterProducts() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

        productCards.forEach(card => {
            const catId = card.getAttribute('data-category');
            const title = card.querySelector('.product-title').textContent.toLowerCase();
            const sku = card.getAttribute('data-sku') ? card.getAttribute('data-sku').toLowerCase() : '';

            const matchesCategory = (activeCategory === 'all' || catId === activeCategory);
            const matchesSearch = (!query || title.includes(query) || sku.includes(query));

            if (matchesCategory && matchesSearch) {
                card.style.display = 'block';
                card.classList.add('animate-scale-in');
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (categoryBtns.length > 0) {
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                categoryBtns.forEach(b => b.classList.remove('active', 'btn-accent'));
                categoryBtns.forEach(b => b.classList.add('btn-outline-custom'));
                
                btn.classList.remove('btn-outline-custom');
                btn.classList.add('active', 'btn-accent');

                activeCategory = btn.getAttribute('data-cat-id');
                filterProducts();
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterProducts);
    }

    // Product Detail Quick View Modal Handler
    const productModal = document.getElementById('productDetailModal');
    if (productModal) {
        productModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            if (!button) return;

            const name = button.getAttribute('data-name');
            const sku = button.getAttribute('data-sku');
            const category = button.getAttribute('data-category-name');
            const innerPack = button.getAttribute('data-inner-pack');
            const outerPack = button.getAttribute('data-outer-pack');
            const description = button.getAttribute('data-description');
            const imagesJson = button.getAttribute('data-images');

            // Set modal text contents
            document.getElementById('modalProdTitle').textContent = name;
            document.getElementById('modalProdSku').textContent = sku;
            document.getElementById('modalProdCat').textContent = category;
            document.getElementById('modalProdInnerPack').textContent = innerPack || '1 Pcs';
            document.getElementById('modalProdOuterPack').textContent = outerPack || '24 Pcs';
            document.getElementById('modalProdDesc').textContent = description || 'High quality Balaji Kitchenware item.';

            // Setup WhatsApp Inquiry Link
            const waNumber = '919876543210';
            const waMsg = `Hello Balaji Kitchenware, I want to inquire about: ${name} (SKU: ${sku}). Please share price quote & minimum order quantity details.`;
            document.getElementById('modalWaBtn').setAttribute('href', `https://api.whatsapp.com/send?phone=${waNumber}&text=${encodeURIComponent(waMsg)}`);

            // Parse & Populate Multi-Image Gallery
            let images = [];
            try {
                images = JSON.parse(imagesJson);
            } catch (e) {
                images = [imagesJson];
            }

            const mainImg = document.getElementById('modalMainImg');
            const thumbsRow = document.getElementById('modalThumbsRow');
            thumbsRow.innerHTML = '';

            if (images && images.length > 0) {
                mainImg.src = `assets/uploads/products/${images[0]}`;
                
                images.forEach((imgSrc, idx) => {
                    const thumbDiv = document.createElement('div');
                    thumbDiv.className = `modal-thumb-item ${idx === 0 ? 'active' : ''}`;
                    thumbDiv.innerHTML = `<img src="assets/uploads/products/${imgSrc}" alt="Thumbnail ${idx+1}">`;
                    
                    thumbDiv.addEventListener('click', () => {
                        document.querySelectorAll('.modal-thumb-item').forEach(t => t.classList.remove('active'));
                        thumbDiv.classList.add('active');
                        mainImg.src = `assets/uploads/products/${imgSrc}`;
                    });

                    thumbsRow.appendChild(thumbDiv);
                });
            } else {
                mainImg.src = 'assets/images/placeholder_product.jpg';
            }
        });
    }

});
