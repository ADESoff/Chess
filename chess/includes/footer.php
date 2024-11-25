<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>О портале</h3>
                <p>Шахматный портал - место, где собираются любители шахмат для участия в турнирах, общения и развития своего мастерства.</p>
            </div>
            <div class="footer-section">
                <h3>Быстрые ссылки</h3>
                <ul>
                    <li><a href="tournaments.php"><i class="fas fa-trophy"></i> Турниры</a></li>
                    <li><a href="leaderboard.php"><i class="fas fa-medal"></i> Рейтинг</a></li>
                    <li><a href="quest.php"><i class="fas fa-tasks"></i> Квест</a></li>
                    <li><a href="news.php"><i class="fas fa-newspaper"></i> Новости</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Контакты</h3>
                <ul>
                    <li><i class="fas fa-envelope"></i> support@chess-portal.ru</li>
                    <li><i class="fas fa-phone"></i> +7 (999) 123-45-67</li>
                    <li><i class="fas fa-map-marker-alt"></i> Москва, Россия</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Социальные сети</h3>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-vk"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-telegram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Шахматный портал. Все права защищены.</p>
        </div>
    </div>
</footer>

<?php if (isset($additional_js)): ?>
    <?php foreach ($additional_js as $js): ?>
        <script src="<?php echo $js; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
