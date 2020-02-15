import "./scss/main.scss";
import "./js/main";
import Vue from "vue";
import Components from "./components";
import axios from "axios";
import Toasted from 'vue-toasted';

Vue.prototype.$http = axios;
Vue.prototype.axios = axios;

Vue.config.productionTip = false;

Vue.use(Toasted, {
    iconPack : 'fontawesome',
    position: 'bottom-right',
    duration: 5000,
    action : {
        text : 'Close',
        onClick : (e, toastObject) => {
            toastObject.goAway(0);
        }
    }
});

new Vue({
    el: "#app",
    delimiters: ["${", "}"]
});
