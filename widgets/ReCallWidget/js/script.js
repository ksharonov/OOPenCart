function ReCall() {

}

ReCall.prototype = {
    constructor: ReCall
};

ReCall.prototype.init = function () {
    this.events();
};

ReCall.prototype.events = function () {

};


var reCall = new ReCall();
reCall.init();