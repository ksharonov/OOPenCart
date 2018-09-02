<?php if ($sliders) { ?>
    <div class="main-slider">
        <?php foreach ($sliders as $slider) { ?>
            <a href="<?= $slider['url'] ?? null ?>">
                <img src="<?= $slider['img'] ?? null ?>" class="main-slider__slide"
                     alt="<?= $slider['title'] ?? null ?>">
            </a>
        <?php } ?>
    </div>
<?php } ?>