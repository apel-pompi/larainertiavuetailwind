import './bootstrap';
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css'; // Import the CSS

import Main from "./Layouts/Main.vue";
import Login from "./Layouts/Login.vue";
import { setThemeOnLoad } from "./theme";

createInertiaApp({
    title: (title) => `My App ${title}`,
    resolve: (name) => {
        
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        
        if(name==='Auth/Login'){
            page.default.layout = page.default.layout || Login;
            return page;
        }else{
            page.default.layout = page.default.layout || Main;
            return page;
        }

        
    },
    setup({ el, App, props, plugin }) {
        // Configure Vue Toastification
        const toastOptions = {
            timeout: 3000, // Toast duration in milliseconds
            position: 'top-right', // Toast position
          };
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, toastOptions)
            .component("Head", Head)
            .component("Link", Link)
            .mount(el);
    },
    progress: {
        color: "#000",
        showSpinner: true,
    },
});


setThemeOnLoad()
