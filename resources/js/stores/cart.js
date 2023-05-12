import {defineStore} from 'pinia'

export const useCartStore = defineStore('cart', {
    state: () => {
        return {
            cartItems: [],
            cart: {},
        }
    },
    actions: {
        initCart(cart) {
            if (!cart.cart) return;
            this.cartItems = [];
            this.cart = {}
            cart.items.data.forEach(_cartItem => this.addItem(_cartItem));
            this.cart = cart.cart;
        },
        addItem(cartItem) {
            this.cartItems.push(cartItem)
        },
        removeItem(_cartItem) {
            this.cartItems = this.cartItems.filter(cartItem => cartItem.id !== _cartItem.id)
        },
        cartHasProduct(_product) {
            return this.cartItems.some(cartItem => cartItem.product.id === _product.id);
        },
        getCartItemByProduct(_product) {
            let foundCartItem = null;
            this.cartItems.forEach(cartItem => {
                if (cartItem.product.id === _product.id)
                    foundCartItem = cartItem;
            })
            return foundCartItem;
        },
        afterCheckout() {
          this.cart = {};
          this.cartItems = [];
        }
    },
    getters: {
        itemsCount: (state) => state.cartItems.length,
    },
})
