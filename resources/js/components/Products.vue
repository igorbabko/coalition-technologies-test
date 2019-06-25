<template>
    <div class="row">
        <div class="col-12">
            <h2>Add product</h2>
            <form @submit.prevent="add">
                <div v-if="errors" class="alert alert-danger" role="alert">
                    <h5>Please, fix validation errors</h5>
                    <ul v-for="field in errors">
                        <li>{{ field[0] }}</li>
                    </ul>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" id="name" name="name" v-model="product.name">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" v-model="product.quantity">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" v-model="product.price">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="col-12 mt-5">
            <template v-if="products.length">
                <h2>All products</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <td scope="col">Product Name</td>
                            <th scope="col">Quantity in Stock</th>
                            <th scope="col">Price Per Item</th>
                            <th scope="col">Submitted</th>
                            <th scope="col">Total</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product, i in products">
                            <td class="editable" ref="name" contenteditable @blur="update(i, product.submitted)">{{ product.name }}</td>
                            <td class="editable" ref="quantity" contenteditable @blur="update(i, product.submitted)">{{ product.quantity }}</td>
                            <td class="editable" ref="price" contenteditable @blur="update(i, product.submitted)">{{ product.price }}</td>
                            <td>{{ product.submitted }}</td>
                            <td>{{ product.quantity * product.price }}</td>
                            <td>
                                <button @click="remove(i, product.submitted)" type="button" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                        <tr class="totals">
                            <td>Totals</td>
                            <td>{{ totalProducts }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ totalPrice }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </template>
            <div v-else>No products</div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                products: [],
                errors: null,
                product: {
                    name: null,
                    quantity: null,
                    price: null
                }
            }
        },

        mounted() {
            axios.get('/products').then((response) => {
                this.products = response.data
            })
        },

        computed: {
            totalProducts() {
                return this.products.reduce((totalProducts, product) => {
                    return totalProducts + product.quantity
                }, 0)
            },
            totalPrice() {
                return this.products.reduce((totalPrice, product) => {
                    return totalPrice + (product.price * product.quantity)
                }, 0)
            }
        },

        methods: {
            add() {
                axios.post('/products', this.product).then((response) => {
                    this.products.unshift(response.data)
                    this.errors = null

                    this.product = {
                        name: null,
                        price: null,
                        quantity: null
                    }
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
            },
            update(id, submitted) {
                let updatedProduct = {
                    name: this.$refs.name[id].textContent,
                    price: this.$refs.price[id].textContent,
                    quantity: this.$refs.quantity[id].textContent,
                    submitted: this.products[id].submitted
                };

                console.log([this.products[id], updatedProduct])

                if (this.isEqual(this.products[id], updatedProduct)) {
                    console.log('we are here')
                    return
                }

                axios.patch('/products', updatedProduct).then((response) => {
                    this.$delete(this.products, id)
                    this.products.unshift(response.data)

                    this.errors = null
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
            },
            remove(id, submitted) {
                axios.delete(`/products?submitted=${submitted}`).then(response => {
                    this.products.splice(id, 1)
                    this.errors = null
                }).catch(error => {
                    this.errors = error.response.data.errors
                })
            },
            isEqual(product, updatedProduct) {
                if (product.name != updatedProduct.name) {
                    return false;
                }

                if (product.price != updatedProduct.price) {
                    return false;
                }

                if (product.quantity != updatedProduct.quantity) {
                    return false;
                }

                return true;
            }
        }
    }
</script>
