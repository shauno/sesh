<template>
    <div>
        <form method="POST" v-on:submit.prevent="submit()">
            <div v-if="errors.generic" class="alert alert-danger">
                {{ errors.generic }}
            </div>

            <div class="form-group">
                <label for="name">MSW Closet Spot for Forecast</label>
                <select v-bind:class="{ 'form-control': true, 'is-invalid': errors.msw_spot_id }" v-model="msw_spot_id">
                    <option v-for="spot in msw_spots" v-bind:value="spot.id">
                        {{ spot.name }}
                    </option>
                </select>
                <div v-if="errors.msw_spot_id" class="invalid-feedback">
                    {{ errors.msw_spot_id[0] }}
                </div>
            </div>

            <div class="form-group">
                <label for="name">Spot Name</label>
                <input id="name" type="text" v-model="name" v-bind:class="{ 'form-control': true, 'is-invalid': errors.name }">
                <div v-if="errors.name" class="invalid-feedback">
                    {{ errors.name[0] }}
                </div>
            </div>

            <div class="custom-control custom-checkbox">
                <input id="is_public" type="checkbox" v-model="is_public" class="custom-control-input">
                <label for="is_public" class="custom-control-label">Public - Everyone can see this spot and logged surfs there</label>
            </div>

            <div class="group float-right">
                <router-link :to="{name: 'home'}" class="btn btn-link btn-md">Cancel</router-link>
                <button type="submit" class="btn btn-primary">Create New Spot</button>
            </div>

        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                msw_spots: [],
                msw_spot_id: '',
                name: '',
                is_public: true,
                errors: []
            }
        },
        created() {
            axios.get("/api/v1/msw_spot")
                .then(response => {
                    this.msw_spots = response.data;
                });
        },
        methods: {
            submit() {
                var self = this;
                axios.post('/api/v1/spot', {
                    'msw_spot_id': this.msw_spot_id,
                    'name': this.name,
                    'public': this.is_public
                }).then(function (response) {
                    self.$router.push({
                        name: 'create-surf',
                        params: {
                            flash_message: 'Your new spot has been created!',
                            selected_spot_id: response.data.id
                        }
                    });
                }).catch(function (error) {
                    console.log(error);
                    if (error.response) {
                        self.errors = error.response.data;
                    }else{
                        self.errors = {generic: 'There was a error trying to submit the form. Are you still connected to the internet?'}
                    }
                });
            }
        }
    }
</script>