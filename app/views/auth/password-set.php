<?php $this->layout('layout', ['title' => "Восстановление пароля"]) ?>

<section class="hero is-dark">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Восстановление пароля.
            </h1>
            <h2 class="subtitle">
                Введите новый пароль.
            </h2>
        </div>
    </div>
</section>
<section class="section main-content">
    <div class="columns is-mobile is-centered">

        <div class="column is-one-third-desktop is-half-tablet is-full-mobile">
            <?= flash(); ?>
            <form action="/password-recovery/change" method="post">
                <input type="hidden" name="selector" value="<?= $data['selector'];?>">
                <input type="hidden" name="token" value="<?= $data['token'];?>">
                <div class="field">
                    <label class="label">Новый пароль</label>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="password" name="password">  <!-- is-danger -->
                        <span class="icon is-small is-left">
                      <i class="fas fa-lock"></i>
                    </span>
                        <!-- <span class="icon is-small is-right">
                          <i class="fas fa-exclamation-triangle"></i>
                        </span> -->
                    </div>
                    <!-- <p class="help is-danger">This email is invalid</p> -->
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-link" type="submit">Отправить</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
