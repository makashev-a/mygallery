<footer class="section hero is-light">
    <div class="content has-text-centered">
        <div class="tabs is-centered">
            <ul>
                <li class=""><a href="/">Главная</a></li>
                <?php foreach (getAllCategories() as $category): ?>
                    <li><a href="/category/<?= $category['id'] ?>"><?= $category['title'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <p>
            <strong>MyGallery</strong> - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit expedita
            consequatur, et. Unde, nulla, dicta.
        </p>
    </div>
</footer>