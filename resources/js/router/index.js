import {createRouter, createWebHistory} from 'vue-router'
import Categories from "../views/Categories.vue";
import Products from "../views/Products.vue";
import Cart from "../views/Cart.vue";
import Checkout from "../views/Checkout.vue";
import FAQ from "../views/FAQ.vue";
import ContactUs from "../views/ContactUs.vue";
import WhoAreWe from "../views/WhoAreWe.vue";
import ProductItem from "../views/ProductItem.vue";

var  store_slug = document.getElementById('app').getAttribute('data-store-slug');


const router = createRouter({
    history: createWebHistory(process.env.STORE_URL),
    routes: [
        {
            path: '/' + store_slug,
            alias: '/ecart-store/' + store_slug,
            name: 'Categories',
            component: Categories
        },
        {
            path: '/' + store_slug + '/products/:id',
            name: 'Products',
            component: Products,
            props: true
        },
        {
            path: '/' + store_slug + '/cart',
            name: 'Cart',
            component: Cart
        },
        {
            path: '/' + store_slug + '/checkout',
            name: 'Checkout',
            component: Checkout
        },
        {
            path: '/'+store_slug +'/faq',
            name: 'FAQ',
            component: FAQ
        },
        {
            path: '/'+store_slug +'/contact-us',
            name: 'ContactUs',
            component: ContactUs
        },
        {
            path: '/'+store_slug +'/who-are-we',
            name: 'WhoAreWe',
            component: WhoAreWe
        },
        {
            path: '/'+store_slug +'/product/:id',
            name: 'Product',
            component: ProductItem,
            props: true

        }
    ]
})

export default router
