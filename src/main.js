import Vue from "vue";
import Components from "./components";
import "./app.css";
import axios from "axios";
import VueAxios from "vue-axios";

Vue.use(VueAxios, axios);

Vue.config.productionTip = false;

new Vue({
    el: "#app",
    delimiters: ["${", "}"]
});