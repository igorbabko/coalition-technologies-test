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
        }
    }
</script>
