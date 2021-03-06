<?= $this->layout('layout', ['title' => "Редактирование - {$photo['title']}"]) ?>

<section class="hero is-warning">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Новые события - новые фотографии!
            </h1>
            <h2 class="subtitle">
                Заполните форму и пополните вашу галерею.
            </h2>
        </div>
    </div>
</section>
<section class="section main-content">

    <div class="columns is-mobile is-centered">

        <div class="column is-half-desktop is-half-tablet is-full-mobile">
            <?= flash(); ?>
            <form action="/photos/<?= $photo['id'];?>/update" method="post" enctype="multipart/form-data">
                <div class="field">
                    <label class="label">Название</label>
                    <div class="control">
                        <input class="input" type="text" name="title" value="<?= $photo['title'];?>">
                    </div>
                </div>

                <div class="field">
                    <label class="label">Краткое описание</label>
                    <div class="control">
                        <textarea class="textarea" name="description"><?= $photo['description'];?></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Выберите категорию</label>
                    <div class="control">
                        <div class="select">
                            <select name="category_id">
                                <?php foreach($categories as $category):?>
                                    <option
                                            <?php if($photo['category_id'] == $category['id']):?>selected <?php endif;?>
                                            value="<?= $category['id'];?>"><?= $category['title'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Выберите картинку</label>
                    <div class="file is-normal has-name">
                        <label class="file-label">
                            <input class="file-input" type="file" name="image">
                            <span class="file-cta">
                                <span class="file-icon">
                                  <i class="fas fa-upload"></i>
                                </span>
                                <span class="file-label">
                                  Выбрать файл
                                </span>
                              </span>


                        </label>
                    </div>
                    <br>
                    <figure class="image">
                        <img src="<?= getImage($photo['image'])?>">
                    </figure>
                </div>

                <div class="field is-grouped">
                <div class="control">
                    <button class="button is-warning is-large" type="submit">Изменить</button>
                </div>
            </div>
            </form>
        </div>

    </div>

</section>
