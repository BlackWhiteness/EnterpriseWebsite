var E = window.wangEditor;
var editor = new E('#editor');
var $text1 = $('#detail-detail');
editor.customConfig.uploadImgServer = '/admin/upload/editUploads';
editor.customConfig.uploadImgMaxLength = 5;
editor.customConfig.uploadFileName = 'image[]';
editor.customConfig.onchange = function (html) {
    $text1.val(html)
};
editor.create();
$text1.val(editor.txt.html());
