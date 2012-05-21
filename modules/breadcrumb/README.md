# Breadcrumb

The breadcrumb module allows you to manage breadcrumb trails in your web application. It also features breadcrumb 'buttons' which occupy the right-hand region of the breadcrumb trail. These can be used as shortcut links to contextual URLs that are not directly on the breadcrumb trail.

## Responds To

Breadcrumb responds to benallfree/click-theme events `before_content` and `after_content`. Breadcrumb automatically initializes and renders a base breadcrumb structure. Breadcrumb structures are only rendered if links are present.

## Events

None

# API

### begin_breadcrumb() 

Begin a new breadcrumb set. This will push previous sets onto a saved stack. New breadcrumb buttons or links will be added to this set.

### end_breadcrumb()

End and render the current breadcrumb set. This will pop the previous breadcrumb set from the stack, if any.

### add_breadcrumb($label, $url)

Add a breadcrumb link to the current set.

### add_breadcrumb_button($label, $url)

Add a breadcrumb button to the current set.