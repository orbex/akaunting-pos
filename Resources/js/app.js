window.Vue = require('vue');

import Pos from '../components/Pos'
import SearchProducts from '../components/SearchProducs'
import BestSellers from '../components/BestSellers'
import Customer from '../components/Customer'

Vue.component('search-produts', SearchProducts);
Vue.component('best-sellers', BestSellers);
Vue.component('customer', Customer);

Vue.prototype.$lang = langPos;

Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency.code,
        minimumFractionDigits: 0
    });
    return formatter.format(value);
});

const app = new Vue({
    el: '#app',
    render: h => h(Pos)
});