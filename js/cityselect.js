(function($){
    $.fn.citySelect = function() {       
        var _this = this;
        var firstOption = "<option value=''>请选择地区</option>";
        var provinceEle = _this.find('select').eq(0);
        var cityEle = _this.find('select').eq(1);
        var districtEle = _this.find('select').eq(2);

        this.loadReigon = function (name, Element, selected) {
            $.ajax({
                url: siteUrl('region'),
                type: 'post',
                dataType: 'html',
                data: {local_name: name, selected: selected},
                success: function(html){
                    Element.html(html);
                }
            })  
        };

        this.clearOption = function () {
            cityEle.empty();
            districtEle.empty();
            cityEle.append(firstOption);
            districtEle.append(firstOption);
        }

        this.init = function () {
            var pSelected = provinceEle.data('selected');
            var cSelected = cityEle.data('selected');
            var dSelected = districtEle.data('selected');
            if(pSelected || cSelected || dSelected){
                _this.loadReigon('', provinceEle, pSelected);
                _this.loadReigon(pSelected, cityEle, cSelected);
                _this.loadReigon(cSelected, districtEle, dSelected);
            }else{            
                provinceEle.append(firstOption);
                cityEle.append(firstOption);
                districtEle.append(firstOption);
                _this.loadReigon('', provinceEle);
            }

            provinceEle.bind('change', function () {
                if($(this).val()){                
                    _this.clearOption();
                    _this.loadReigon($(this).val(), cityEle);
                }else{
                    _this.clearOption();
                }
            });
            cityEle.bind('change', function () {
                if($(this).val()){
                    _this.loadReigon($(this).val(), districtEle);
                }else{
                    districtEle.empty();
                    districtEle.append(firstOption);
                }
            });
        };

        _this.init();
    };
})(jQuery);