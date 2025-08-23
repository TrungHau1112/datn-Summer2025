(function ($) {
    "use strict"
    var TGNT = {}
    function renderSlug(title) {
            const a = 'àáäâãåăæąçćčđďèéěėëêęğǵḧìíïîįłḿǹńňñòóöôœøṕŕřßşśšșťțùúüûǘůűūųẃẍÿýźžż·/_,:;'
            const b = 'aaaaaaaaacccddeeeeeeegghiiiiilmnnnnooooooprrsssssttuuuuuuuuuwxyyzzz------'
            const p = new RegExp(a.split('').join('|'), 'g')
            return title.toString().toLowerCase()
            .replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a')
            .replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e')
            .replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i')
            .replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o')
            .replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u')
            .replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y')
            .replace(/đ/gi, 'd')
            .replace(/\s+/g, '-') 
            .replace(p, c => b.charAt(a.indexOf(c)))
            .replace(/&/g, '-and-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '')
    }

    TGNT.meta_title = () => {
        $(document).on('keyup', '#meta_title', function () {
            let title = $(this).val()
            $('.count_meta_title').html(title.length)
            $('.value_seo_slug').html(renderSlug(title))
            if(title.length > 60){
                $('.count_meta_title').css('color', 'red');
            } else {
                $('.count_meta_title').css('color', 'green');
            }
            $('.value_seo_title').html(title)
        })
    }

    TGNT.meta_description = () => {
        $(document).on('keyup', '#meta_description', function () {
            let title = $(this).val()
            $('.count_meta_description').html(title.length)
            if(title.length > 160){
                $('.count_meta_description').css('color', 'red');
            } else {
                $('.count_meta_description').css('color', 'green');
            }
            $('.value_seo_description').html(title)
        })
    }
    $(document).ready(function () {
        TGNT.meta_title()
        TGNT.meta_description()
    })





})(jQuery)
