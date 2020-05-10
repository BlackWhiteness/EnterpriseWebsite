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

function splitVideoPre() {
    let html = '';
    html += '<div class="img-pre"><span class="close" onclick="delImage(this)">&times;</span><video src="';
    html += res.path;
    html += '" controls  width="50%" height="50%"></video><input type="hidden" name="video" value="';
    html += res.path + '"></div>';
    return html;

}
