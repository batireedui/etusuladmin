<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etusul - Багц сонгох</title>
    <link rel="stylesheet" href="css/payment.css">
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <a href="dashboard.php" class="back-btn">← Буцах</a>
            <h1>Багц сонгох</h1>
            <p>Танай бизнест тохирсон багцаа сонгоод идэвхжүүлээрэй</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="pricing-toggle">
            <div class="toggle-info">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                </svg>
                <span>Бүх багц зөвхөн <strong>жилийн төлбөртэй</strong></span>
            </div>
        </div>

        <div class="pricing-cards">
            <!-- Etusul Basic -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Etusul Basic</h3>
                    <p class="card-subtitle">Жижиг бизнест</p>
                </div>
                <div class="card-price">
                    <div class="price-wrapper">
                        <span class="price-main">864,000₮</span>
                        <span class="price-period">/жил</span>
                    </div>
                    <div class="price-breakdown">
                        <span class="monthly-equivalent">📅 ≈ 72,000₮ сард</span>
                    </div>
                </div>
                <ul class="features-list">
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        5 төсөл, 5 хэрэглэгч
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Өдөр тутмын тайлан + фото
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Албан тоот
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Орлогын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Зардлын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Компанийн баримт бичгийн сан
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Төслийн баримт бичгийн сан
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Mobile app
                    </li>
                    <li class="disabled">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="18" y1="6" x2="6" y2="18" stroke-width="2"/>
                            <line x1="6" y1="6" x2="18" y2="18" stroke-width="2"/>
                        </svg>
                        Бараа Материалын удирдлага
                    </li>
                    <li class="disabled">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="18" y1="6" x2="6" y2="18" stroke-width="2"/>
                            <line x1="6" y1="6" x2="18" y2="18" stroke-width="2"/>
                        </svg>
                        Ил далд ажлын актууд
                    </li>
                </ul>
                <button class="select-btn" onclick="selectPackage('basic', this)">Сонгох</button>
            </div>

            <!-- Etusul Pro -->
            <div class="pricing-card featured">
                <div class="popular-badge">Түгээмэл</div>
                <div class="card-header">
                    <h3>Etusul Pro</h3>
                    <p class="card-subtitle">Дунд бизнест</p>
                </div>
                <div class="card-price">
                    <div class="price-wrapper">
                        <span class="price-main">1,800,000₮</span>
                        <span class="price-period">/жил</span>
                    </div>
                    <div class="price-breakdown">
                        <span class="monthly-equivalent">📅 ≈ 150,000₮ сард</span>
                    </div>
                </div>
                <ul class="features-list">
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        10 төсөл, 10 хэрэглэгч
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Өдөр тутмын тайлан + фото
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Албан тоот
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Орлогын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Зардлын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Бараа Материалын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Ил далд ажлын актууд
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Компанийн баримт бичгийн сан
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Төслийн баримт бичгийн сан
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Mobile app
                    </li>
                    <li class="disabled">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="18" y1="6" x2="6" y2="18" stroke-width="2"/>
                            <line x1="6" y1="6" x2="18" y2="18" stroke-width="2"/>
                        </svg>
                        Тендер модуль
                    </li>
                    <li class="disabled">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <line x1="18" y1="6" x2="6" y2="18" stroke-width="2"/>
                            <line x1="6" y1="6" x2="18" y2="18" stroke-width="2"/>
                        </svg>
                        Нярав модуль
                    </li>
                </ul>
                <button class="select-btn" onclick="selectPackage('pro', this)">Сонгох</button>
            </div>

            <!-- Etusul Premium - REDEEM КОД -->
            <div class="pricing-card premium-special">
                <div class="exclusive-badge">🎁 Онцгой урилга</div>
                <div class="card-header">
                    <h3>Etusul Premium</h3>
                    <p class="card-subtitle">Том бизнест</p>
                </div>
                <div class="card-price">
                    <div class="price-wrapper">
                        <span class="price-main">Redeem код</span>
                        <span class="price-period">шаардлагатай</span>
                    </div>
                    <div class="price-breakdown">
                        <span class="monthly-equivalent"> 📅 Зөвхөн урилгаар</span>
                    </div>
                </div>
                <ul class="features-list">
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Хязгааргүй төсөл + хэрэглэгч
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Өдөр тутмын тайлан + фото
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Албан тоот
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Орлогын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Зардлын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Төлбөрийн хуваарь
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Бараа Материалын удирдлага
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Ил далд ажлын актууд
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Нярав модуль
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Компанийн баримт бичгийн сан
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Төслийн баримт бичгийн сан
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Тендер модуль
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        API холболт
                    </li>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                        </svg>
                        Mobile app
                    </li>
                </ul>
                <button class="select-btn redeem-btn" onclick="showRedeemModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin-right: 8px;">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke-width="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke-width="2"/>
                    </svg>
                    Код оруулах
                </button>
            </div>
        </div>

        <!-- REDEEM КОД MODAL -->
        <div id="redeemModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeRedeemModal()">&times;</span>
                <h2>🎁 Premium Redeem код оруулах</h2>
                <p class="modal-description">Etusul Premium багцын идэвхжүүлэх кодоо оруулна уу</p>
                
                <form method="POST" action="" class="redeem-form">
                    <div class="form-group">
                        <label for="redeem_code">Redeem код:</label>
                        <input 
                            type="text" 
                            id="redeem_code" 
                            name="redeem_code" 
                            class="redeem-input" 
                            placeholder="PREMIUM2026-XXXXXX"
                            required
                            maxlength="50"
                            autocomplete="off"
                        >
                        <small class="help-text">Жишээ: PREMIUM2026-ABC123DEF456</small>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeRedeemModal()">Буцах</button>
                        <button type="submit" class="btn btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin-right: 8px;">
                                <polyline points="20 6 9 17 4 12" stroke-width="2"/>
                            </svg>
                            Идэвхжүүлэх
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ТӨЛБӨР БАТАЛГААЖУУЛАХ MODAL (Basic, Pro) -->
        <div id="confirmModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Төлбөр баталгаажуулах</h2>
                <div class="confirm-details">
                    <p><strong>Багц:</strong> <span id="selectedPackage"></span></p>
                    
                    <div class="duration-selector">
                        <p class="duration-label"><strong>Хугацаа сонгох:</strong></p>
                        <div class="duration-options">
                            <label class="duration-option">
                                <input type="radio" name="duration" value="1" checked>
                                <span class="duration-card">
                                    <span class="duration-years">1 жил</span>
                                    <span class="duration-price" id="price-1year"></span>
                                    <span class="duration-badge discount-10">-10%</span>
                                </span>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="3">
                                <span class="duration-card">
                                    <span class="duration-years">3 жил</span>
                                    <span class="duration-price" id="price-3year"></span>
                                    <span class="duration-badge discount-20">-20%</span>
                                </span>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="5">
                                <span class="duration-card">
                                    <span class="duration-years">5 жил</span>
                                    <span class="duration-price" id="price-5year"></span>
                                    <span class="duration-badge discount-30">-30%</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="total-section">
                        <p><strong>Нийт дүн:</strong> <span id="totalAmount"></span></p>
                    </div>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="package" id="packageInput">
                    <input type="hidden" name="duration" id="durationInput">
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Буцах</button>
                        <button type="submit" class="btn btn-primary">Төлбөр төлөх</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const basePrices = {
            'basic': 864000,
            'pro': 1800000
        };

        let currentPackage = '';

        function selectPackage(packageName, button) {
            if (packageName === 'premium') {
                showRedeemModal();
                return;
            }
            
            currentPackage = packageName;
            
            const packageNames = {
                'basic': 'Etusul Basic',
                'pro': 'Etusul Pro'
            };
            
            document.getElementById('selectedPackage').textContent = packageNames[packageName];
            document.getElementById('packageInput').value = packageName;
            
            updatePrices(packageName);
            document.querySelector('input[name="duration"][value="1"]').checked = true;
            updateTotal(1);
            
            document.getElementById('confirmModal').style.display = 'block';
        }

        function updatePrices(packageName) {
            const basePrice = basePrices[packageName];
            
            const price1year = basePrice * 0.9;
            document.getElementById('price-1year').textContent = formatPrice(price1year);
            
            const price3year = basePrice * 3 * 0.8;
            document.getElementById('price-3year').textContent = formatPrice(price3year);
            
            const price5year = basePrice * 5 * 0.7;
            document.getElementById('price-5year').textContent = formatPrice(price5year);
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('mn-MN').format(Math.round(price)) + '₮';
        }

        function updateTotal(years) {
            const basePrice = basePrices[currentPackage];
            let totalPrice;
            
            switch(years) {
                case 1:
                    totalPrice = basePrice * 0.9;
                    break;
                case 3:
                    totalPrice = basePrice * 3 * 0.8;
                    break;
                case 5:
                    totalPrice = basePrice * 5 * 0.7;
                    break;
            }
            
            document.getElementById('totalAmount').textContent = formatPrice(totalPrice);
            document.getElementById('durationInput').value = years * 12;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const durationRadios = document.querySelectorAll('input[name="duration"]');
            durationRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateTotal(parseInt(this.value));
                });
            });
        });

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        function showRedeemModal() {
            document.getElementById('redeemModal').style.display = 'block';
        }

        function closeRedeemModal() {
            document.getElementById('redeemModal').style.display = 'none';
        }

        document.querySelector('.close').addEventListener('click', closeModal);
        
        window.onclick = function(event) {
            const confirmModal = document.getElementById('confirmModal');
            const redeemModal = document.getElementById('redeemModal');
            
            if (event.target === confirmModal) {
                closeModal();
            }
            if (event.target === redeemModal) {
                closeRedeemModal();
            }
        }
    </script>
</body>
</html>