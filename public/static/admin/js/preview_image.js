function splitImagePre(name) {
    let html = '';
    html += '<div class="img-pre"><span class="close" onclick="delImage(this)">&times;</span>';
    html += '<img src="' + name + '" class="layui-upload-img">';
    html += '<input type="hidden" name="imgs[]" value="' + name + '"  autocomplete="off" placeholder="" class="layui-input"></div>';
    return html;
}

function delImage(obj) {
    obj.parentNode.remove();
}