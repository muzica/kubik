(function($) {
  Drupal.OnePage = Drupal.OnePage || {};
  Drupal.OnePage.currentRegion = null;
  Drupal.OnePage.regions = [];
  Drupal.OnePage.clickScrolling = false;
  Drupal.OnePage.menuItems = [];
    
  Drupal.behaviors.scrollMenuAction = {
    attach: function (context) {
      if ($('body.front').length) {
        $('body.front #page').children().each(function() {
          id = $(this).attr('id');
          menu_item = $('#main-menu-wrapper ul.menu li .' + id);
          if(menu_item.length) {
            Drupal.OnePage.menuItems.push(menu_item)
            menu_item.attr('data-id', id);
            menu_item.attr('data-href', menu_item.attr('href'));
            menu_item.attr('data-link-type', 'scroll');
            menu_item.attr('data-hash', Drupal.settings.basePath + "#" + id);
            menu_item.attr('href', Drupal.settings.basePath + "#" + id);
          }
        });
        for(x in Drupal.OnePage.menuItems) {
          menu_item = Drupal.OnePage.menuItems[x];
          $(menu_item).click(function() {
            href = $(this).attr('href');
            id = $(this).attr('data-id');
            href = $(this).attr('data-href');
            data_type = $(this).attr('data-link-type');
            if (data_type == 'scroll') {
              self = $(this);
              window.setTimeout(function() {
                for(xx in Drupal.OnePage.menuItems) {
                  m_item = Drupal.OnePage.menuItems[xx];
                  m_item.attr('href', m_item.attr('data-hash'));
                }
                self.attr('href', href);
              }, 300);
            }
          });
        };
        $(window).load(function() {
          for(x in Drupal.OnePage.menuItems) {
            var menu_item = Drupal.OnePage.menuItems[x];
            var id = $(menu_item).attr('data-id');
            var element = $("#" + id);
            if(element.length) {
              var offset = $(element).offset();
              $(menu_item).attr('data-top', offset.top);
              $(menu_item).attr('data-bottom', offset.top + $(element).outerHeight());
            }
          }
          $(window).scroll(function() {
            var top = $(window).scrollTop();
            var bottom = top + $(window).height();
            var selected_item = -1;
            var current_delta = 0;
            var min_delta = 10000;
            for(i = 0; i < Drupal.OnePage.menuItems.length; i ++) {
              var menu_item = Drupal.OnePage.menuItems[i];
              var i_top = $(menu_item).attr('data-top');
              var i_bottom = $(menu_item).attr('data-bottom');
              current_delta = Math.abs(top - i_top + bottom - i_bottom);
              if(current_delta < min_delta) {
                min_delta = current_delta;
                selected_item = i;
              }
            }
            if (selected_item !== Drupal.OnePage.currentRegion) {
              for (j = 0; j < Drupal.OnePage.menuItems.length; j ++) {
                if (j !== selected_item) {
                  $(Drupal.OnePage.menuItems[j]).removeClass('active');
                }
              }
              $(Drupal.OnePage.menuItems[selected_item]).addClass('active');
            }
          });
        });
      }
    }
  };
})(jQuery);
