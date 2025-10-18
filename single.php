<?php get_header(); ?>
<main>
  <div class="container">
    <article class="single-post">
      <?php if (have_posts()):
        while (have_posts()):
          the_post(); ?>
          <header class="post-header">
            <div class="post-tags">
              <span><?php echo get_the_date('j F'); ?></span>
              <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle opacity="0.5" cx="2" cy="2" r="2" fill="currentColor" />
              </svg>
              <a href="<?php
              $categories = get_the_category();
              $category = !empty($categories) ? $categories[0] : null;
              echo $category ? esc_url(get_category_link($category->term_id)) : esc_url(home_url('/category/prochee/'));
              ?>" class="post-tags-icon" aria-label="Перейти к категории <?php
              $category_name = $category ? esc_html($category->name) : 'Прочее';
              echo esc_attr($category_name);
              ?>">
                <?php
                $category_slug = $category ? $category->slug : 'prochee';
                $category_icons = tgh_get_category_icons();
                $icon_url = isset($category_icons[$category_slug]) ? $category_icons[$category_slug] : $category_icons['prochee'];
                $icon_path = str_replace(get_site_url(), ABSPATH, $icon_url);

                if (WP_DEBUG) {
                  error_log('Post: ' . get_the_title() . ', Category: ' . $category_name . ', Slug: ' . $category_slug . ', Icon: ' . $icon_url);
                }

                if (file_exists($icon_path)) {
                  $svg_content = file_get_contents($icon_path);
                  if ($svg_content && stripos($svg_content, '<svg') !== false) {
                    $svg_content = preg_replace('/<mask[^>]*>.*?<\/mask>/s', '', $svg_content);
                    $svg_content = preg_replace('/<g[^>]*mask="[^"]*"[^>]*>/', '<g>', $svg_content);
                    $svg_content = preg_replace('/fill="#15152A"/i', 'stroke="currentColor" fill="none"', $svg_content);
                    $svg_content = preg_replace('/(id|class|style)="[^"]*"/i', '', $svg_content);
                    $svg_content = preg_replace('/stroke="[^"]*"/i', 'stroke="currentColor"', $svg_content);
                    if (!preg_match('/viewBox="[^"]*"/i', $svg_content)) {
                      $svg_content = preg_replace('/<svg/i', '<svg viewBox="0 0 24 24"', $svg_content);
                    }
                    $svg_content = preg_replace('/<svg/i', '<svg role="img" aria-hidden="true"', $svg_content);
                    echo '<span class="post-category-icon">' . $svg_content . '</span>';
                  } else {
                    if (WP_DEBUG) {
                      error_log('Invalid SVG for category: ' . $category_name);
                    }
                  }
                } else {
                  if (WP_DEBUG) {
                    error_log('SVG not found: ' . $icon_path);
                  }
                }
                ?>
                <span class="post-category-name"><?php echo $category_name; ?></span>
              </a>
            </div>
            <h1 class="single-post-title"><?php the_title(); ?></h1>
            <div class="post-author">
              <span
                class="post-author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 30, 'mystery', 'Аватар пользователя'); ?></span>
              <span class="post-author-name"><?php the_author(); ?></span>
            </div>
          </header>
          <div class="post-content">
            <?php the_content(); ?>
            <?php
            echo do_shortcode('[banner_pulse]');
            ?>
          </div>
          <?php if (get_field('external_source')): ?>
            <div class="external-links">
              <p>Источник: <a href="<?php echo esc_url(get_field('external_source')); ?>" target="_blank"
                  class="source-link">Перейти</a></p>
            </div>
          <?php endif; ?>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      <?php else: ?>
        <p>Пост не найден.</p>
      <?php endif; ?>
    </article>
  </div>
</main>
<?php get_footer(); ?>