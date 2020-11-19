$(function () {
    var j$ = jQuery,
        $navMenu = j$("#nav_survey_menu"),
        $slideActiveLine = j$("#slide-active-line"),
        $currentActiveItem = j$(".slide-active");

    j$(function () {
        // Menu has active item
        if ($currentActiveItem[0]) {
            $slideActiveLine.css({
                "width": $currentActiveItem.width(),
                "left": $currentActiveItem.position().left
            });
        }

        // Underline transition
        j$($navMenu).find("li").hover(

            // Hover on
            function () {
                $slideActiveLine.css({
                    "width": j$(this).width(),
                    "left": j$(this).position().left
                });
            },
            // Hover out
            function () {
                if ($currentActiveItem[0]) {
                    // Go back to current
                    $slideActiveLine.css({
                        "width": $currentActiveItem.width(),
                        "left": $currentActiveItem.position().left
                    });
                } else {
                    // Disapear
                    $slideActiveLine.width(0);
                }
            }
        );
    });
});
