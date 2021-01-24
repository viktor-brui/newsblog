<header class="masthead" style="background-image: url('/public/materials/<?php echo $data['id']; ?>.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="page-heading">
                    <h1><?php echo htmlspecialchars($data['name'], ENT_QUOTES); ?></h1>
                    <span class="subheading"><?php echo htmlspecialchars($data['description']); ?></span>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <p><?php echo $data['text']; ?></p>
            <form action="/rating" method="post">
                <fieldset>
                    <legend><?php echo $data['rating'] ? 'Текущая оценка: ' . $data['rating'] : 'Оставьте оценку:'; ?></legend>
                    <div class="rating__group">
                        <input class="rating__input" type="radio" id="star-1" name="rating" value="1">
                        <label class="rating__star" for="star-1" title="Ужасно" area-label="Ужасно"></label>

                        <input class="rating__input" type="radio" id="star-2" name="rating" value="2">
                        <label class="rating__star" for="star-2" title="Плохо»" area-label="Плохо»"></label>

                        <input class="rating__input" type="radio" id="star-3" name="rating" value="3">
                        <label class="rating__star" for="star-3" title="Нормально»" area-label="Нормально»"></label>

                        <input class="rating__input" type="radio" id="star-4" name="rating" value="4">
                        <label class="rating__star" for="star-4" title="Хорошо" area-label="Хорошо"></label>

                        <input class="rating__input" type="radio" id="star-5" name="rating" value="5">
                        <label class="rating__star" for="star-5" title="Отлично" area-label="Отлично"></label>

                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                        <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">

                        <div class=rating__focus></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Добавить</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>