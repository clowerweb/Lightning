import "./scss/main.scss";
import "./js/main";
import Vue from "vue";
import Components from "./components";
import axios from "axios";

Vue.prototype.$http = axios;
Vue.prototype.axios = axios;

Vue.config.productionTip = false;

new Vue({
    el: "#app",
    delimiters: ["${", "}"]
});
