/******/ (function(modules) { // webpackBootstrap
    /******/ 	// The module cache
    /******/ 	var installedModules = {};
    /******/
    /******/ 	// The require function
    /******/ 	function __webpack_require__(moduleId) {
        /******/
        /******/ 		// Check if module is in cache
        /******/ 		if(installedModules[moduleId]) {
            /******/ 			return installedModules[moduleId].exports;
            /******/ 		}
        /******/ 		// Create a new module (and put it into the cache)
        /******/ 		var module = installedModules[moduleId] = {
            /******/ 			i: moduleId,
            /******/ 			l: false,
            /******/ 			exports: {}
            /******/ 		};
        /******/
        /******/ 		// Execute the module function
        /******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Flag the module as loaded
        /******/ 		module.l = true;
        /******/
        /******/ 		// Return the exports of the module
        /******/ 		return module.exports;
        /******/ 	}
    /******/
    /******/
    /******/ 	// expose the modules object (__webpack_modules__)
    /******/ 	__webpack_require__.m = modules;
    /******/
    /******/ 	// expose the module cache
    /******/ 	__webpack_require__.c = installedModules;
    /******/
    /******/ 	// define getter function for harmony exports
    /******/ 	__webpack_require__.d = function(exports, name, getter) {
        /******/ 		if(!__webpack_require__.o(exports, name)) {
            /******/ 			Object.defineProperty(exports, name, {
                /******/ 				configurable: false,
                /******/ 				enumerable: true,
                /******/ 				get: getter
                /******/ 			});
            /******/ 		}
        /******/ 	};
    /******/
    /******/ 	// getDefaultExport function for compatibility with non-harmony modules
    /******/ 	__webpack_require__.n = function(module) {
        /******/ 		var getter = module && module.__esModule ?
            /******/ 			function getDefault() { return module['default']; } :
            /******/ 			function getModuleExports() { return module; };
        /******/ 		__webpack_require__.d(getter, 'a', getter);
        /******/ 		return getter;
        /******/ 	};
    /******/
    /******/ 	// Object.prototype.hasOwnProperty.call
    /******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
    /******/
    /******/ 	// __webpack_public_path__
    /******/ 	__webpack_require__.p = "/";
    /******/
    /******/ 	// Load entry module and return exports
    /******/ 	return __webpack_require__(__webpack_require__.s = 1);
    /******/ })
/************************************************************************/
/******/ ({

    /***/ "./resources/assets/js/fileupload.js":
    /***/ (function(module, exports) {

        /**
         * Created by yaoyao on 2017/8/27.
         */

        (function ($) {
            debugger;
            upload = function upload(arg) {
                var name = "你好";
                var token = arg.token;
                var uid = arg.uid;
                var datatag = arg.tag;
                var pictype = arg.pictype;
                var maxsize = arg.maxsize == "" ? 100 : arg.maxsize;
                var control = $('#' + arg.uploadid);
                this.testFun = function () {
                    //this.testFun方法，加上了this，就是公有方法了，外部可以访问。
                    alert(arg.title + "," + name);
                };


                this.initUpload = function (filestr, _fileroot) {
                    // console.log(filestr);
                    var initdata_1 = uploadInit(filestr, _fileroot);
                    control.fileinput({
                        language: "zh",
                        allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg', 'mp4', 'avi', 'xlsx', 'xls', 'xlsx', 'doc', 'docx'], //接收的文件后缀
                        uploadUrl: '/files/upload',
                        uploadExtraData: {
                            'uid': uid,
                            'pictype': pictype,
                            'filename': arg.uploadid,
                            '_token': token
                        },
                        initialPreview: initdata_1.preview,
                        initialPreviewConfig: initdata_1.preconfig,
                        maxFileSize: maxsize,
                        showRemove: false,
                        showUpload: false,
                        showCancel: false,
                        showClose: false,
                        overwriteInitial: false,
                        showCaption: false
                    });
                    control.on('fileuploaded', function (event, data, previewid, index) {
                        // console.log("上传ccc");
                        if (data.response.code == 1) {
                            builPicdata(datatag, data.response.data);
                        }
                    });
                    control.on("filebatchselected", function (event, files) {
                        $(this).fileinput("upload");
                    });
                    ////overwriteInitial  == False  清除按钮不能修改Init数据 所有警用清除按钮 showClose =  false
                    //control.on("fileclear", function(event, files) {
                    //     $('#' + datatag).val("");
                    //});
                    control.on('filedeleted', function (event, key, jqXHR, data) {
                        // console.log("文件删除");
                        deletePicdata(datatag, key);
                    });
                };

                var builPicdata = function builPicdata(dataid, data) {
                    var obj;
                    if ($('#' + dataid).val() != "") {
                        obj = JSON.parse($('#' + dataid).val());
                    } else {
                        obj = {};
                    }
                    console.log("11111:"+data.id);
                    obj[data.id] = data;
                    $('#' + dataid).val(JSON.stringify(obj));
                };

                var deletePicdata = function deletePicdata(dataid, media_key) {

                    // console.log("删除文件 跟新数据 Key=" + media_key);
                    // console.log("删除文件 跟新数据 Key=" + $('#' + dataid).val());
                    obj = JSON.parse($('#' + dataid).val());
                    // console.log(obj);
                    delete obj[media_key];
                    $('#' + dataid).val(JSON.stringify(obj));
                };
                var uploadInit = function uploadInit(filestr, _fileroot) {
                    var initData = {};
                    var preview = [];
                    var preconfig = [];
                    if (filestr != "") {
                        var save_obj = JSON.parse(filestr); //转换为json对象
                        var arr = ['image/png', 'image/jpeg'];
                        $.each(save_obj, function () {
                            if (this != "") {
                                if ($.inArray(this.m_format, arr) == -1) {
                                    preview.push("<div class='file-preview-text'><div class='file-preview-other'>" + this.m_name + "<span class='file-icon-4x'><i class='fa fa-file-text-o text-info'></i></span></div></div>");
                                } else {
                                    preview.push("<img src='" + _fileroot + this.m_url + "' class='file-preview-image' alt='" + this.m_name + "' title='" + this.m_name + "'>");
                                }
                                var obj = {};
                                obj.caption = this.m_name;
                                obj.url = "/files/del";
                                obj.key = this.id;
                                preconfig.push(obj);
                            }
                        });
                    }
                    initData.preview = preview;
                    initData.preconfig = preconfig;

                    console.log("test1"+initData);
                    return initData;
                };
            };
        })(jQuery);

        (function ($) {
            "use strict";

            $.fn.fileinputLocales['zh'] = {
                fileSingle: '文件',
                filePlural: '个文件',
                browseLabel: '选择 &hellip;',
                removeLabel: '移除',
                removeTitle: '清除选中文件',
                cancelLabel: '取消',
                cancelTitle: '取消进行中的上传',
                uploadLabel: '上传',
                uploadTitle: '上传选中文件',
                msgNo: '没有',
                msgNoFilesSelected: '',
                msgCancelled: '取消',
                msgZoomModalHeading: '详细预览',
                msgFileRequired: '必须选择一个文件上传.',
                msgSizeTooSmall: '文件 "{name}" (<b>{size} KB</b>) 必须大于限定大小 <b>{minSize} KB</b>.',
                msgSizeTooLarge: '文件 "{name}" (<b>{size} KB</b>) 超过了允许大小 <b>{maxSize} KB</b>.',
                msgFilesTooLess: '你必须选择最少 <b>{n}</b> {files} 来上传. ',
                msgFilesTooMany: '选择的上传文件个数 <b>({n})</b> 超出最大文件的限制个数 <b>{m}</b>.',
                msgFileNotFound: '文件 "{name}" 未找到!',
                msgFileSecured: '安全限制，为了防止读取文件 "{name}".',
                msgFileNotReadable: '文件 "{name}" 不可读.',
                msgFilePreviewAborted: '取消 "{name}" 的预览.',
                msgFilePreviewError: '读取 "{name}" 时出现了一个错误.',
                msgInvalidFileName: '文件名 "{name}" 包含非法字符.',
                msgInvalidFileType: '不正确的类型 "{name}". 只支持 "{types}" 类型的文件.',
                msgInvalidFileExtension: '不正确的文件扩展名 "{name}". 只支持 "{extensions}" 的文件扩展名.',
                msgFileTypes: {
                    'image': 'image',
                    'html': 'HTML',
                    'text': 'text',
                    'video': 'video',
                    'audio': 'audio',
                    'flash': 'flash',
                    'pdf': 'PDF',
                    'object': 'object'
                },
                msgUploadAborted: '该文件上传被中止',
                msgUploadThreshold: '处理中...',
                msgUploadBegin: '正在初始化...',
                msgUploadEnd: '完成',
                msgUploadEmpty: '无效的文件上传.',
                msgValidationError: '验证错误',
                msgLoading: '加载第 {index} 文件 共 {files} &hellip;',
                msgProgress: '加载第 {index} 文件 共 {files} - {name} - {percent}% 完成.',
                msgSelected: '{n} {files} 选中',
                msgFoldersNotAllowed: '只支持拖拽文件! 跳过 {n} 拖拽的文件夹.',
                msgImageWidthSmall: '宽度的图像文件的"{name}"的必须是至少{size}像素.',
                msgImageHeightSmall: '图像文件的"{name}"的高度必须至少为{size}像素.',
                msgImageWidthLarge: '宽度的图像文件"{name}"不能超过{size}像素.',
                msgImageHeightLarge: '图像文件"{name}"的高度不能超过{size}像素.',
                msgImageResizeError: '无法获取的图像尺寸调整。',
                msgImageResizeException: '错误而调整图像大小。<pre>{errors}</pre>',
                msgAjaxError: '{operation} 发生错误. 请重试!',
                msgAjaxProgressError: '{operation} 失败',
                ajaxOperations: {
                    deleteThumb: '删除文件',
                    uploadThumb: '上传文件',
                    uploadBatch: '批量上传',
                    uploadExtra: '表单数据上传'
                },
                //dropZoneTitle: '拖拽文件到这里 &hellip;<br>支持多文件同时上传',
                dropZoneTitle: '拖拽文件到这里',
                dropZoneClickTitle: '<br>(或点击{files}按钮选择文件)',
                fileActionSettings: {
                    removeTitle: '删除文件',
                    uploadTitle: '上传文件',
                    zoomTitle: '查看详情',
                    dragTitle: '移动 / 重置',
                    indicatorNewTitle: '没有上传',
                    indicatorSuccessTitle: '上传',
                    indicatorErrorTitle: '上传错误',
                    indicatorLoadingTitle: '上传 ...'
                },
                previewZoomButtonTitles: {
                    prev: '预览上一个文件',
                    next: '预览下一个文件',
                    toggleheader: '缩放',
                    fullscreen: '全屏',
                    borderless: '无边界模式',
                    close: '关闭当前预览'
                }
            };
        })(window.jQuery);

        /***/ }),

    /***/ 1:
    /***/ (function(module, exports, __webpack_require__) {

        module.exports = __webpack_require__("./resources/assets/js/fileupload.js");


        /***/ })

    /******/ });