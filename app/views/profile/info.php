<?php $this->layout('layout', ['title' => "Профиль"]) ?>

<section class="section main-content">

    <div class="columns">
        <div class="column">
            <div class="tabs is-centered pt-100">
                <ul>
                    <li class="is-active">
                        <a href="/profile/info">
                            <span class="icon is-small"><i class="fas fa-user"></i></span>
                            <span>Основная информация</span>
                        </a>
                    </li>
                    <li>
                        <a href="/profile/security">
                            <span class="icon is-small"><i class="fas fa-lock"></i></span>
                            <span>Безопасность</span>
                        </a>
                    </li>
                </ul>

            </div>
            <div class="is-clearfix"></div>
            <div class="columns is-centered">
                <div class="column is-half">
                    <?php echo flash(); ?>
                    <form action="/profile/info" method="post" enctype="multipart/form-data">
                        <div class="field">
                            <label class="label">Ваше имя</label>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input" type="text" value="<?= $user['username']; ?>" name="username">
                                <span class="icon is-small is-left">
                              <i class="fas fa-user"></i>
                            </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input" type="text" value="<?= $user['email']; ?>" name="email">
                                <span class="icon is-small is-left">
                                      <i class="fas fa-user"></i>
                                    </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Аватар</label>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input" type="file" name="image"> <br><br>
                                <img src="<?= getImage($user['image']) ?>" alt="">
                            </div>
                        </div>

                        <div class="control pb-2">
                            <button class="button is-link" type="submit">Обновить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
