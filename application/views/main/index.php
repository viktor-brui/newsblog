<header class="masthead" style="background-image: url('/public/images/home-bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="site-heading">
                    <h1>Мой PHP блог</h1>
                    <span class="subheading">простой блог на php - mvc</span>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <?php if (empty($list)): ?>
                <p>Список постов пуст</p>
            <?php else: ?>
                <a href="?order=date">Сортировать по дате</a>
                <a href="?order=view">Сортировать по просморам</a>
                <?php foreach ($list as $val): ?>

                    <div class="post-preview">
                        <a href="/post/<?php echo $val['id']; ?>">
                            <h2 class="post-title"><?php echo htmlspecialchars($val['name'], ENT_QUOTES); ?></h2>
                            <h5 class="post-subtitle"><?php echo htmlspecialchars($val['description'], ENT_QUOTES); ?></h5>
                        </a>
                        <p class="post-date"><?php echo htmlspecialchars($val['date'], ENT_QUOTES); ?></p>
                        <img class="img-preview-post" src="/public/materials/<?php echo $val['id']; ?>.jpg" alt="preview img"/>
                    </div>
                    <hr>
                <?php endforeach; ?>
                <div class="clearfix">
                    <?php echo $pagination; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>