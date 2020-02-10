import "./scss/main.scss";
import "./js/main";
import Vue from "vue";
import Components from "./components";
import axios from "axios";
import VueAxios from "vue-axios";

Vue.use(VueAxios, axios);

Vue.config.productionTip = false;

new Vue({
    el: "#app",
    delimiters: ["${", "}"]
});
