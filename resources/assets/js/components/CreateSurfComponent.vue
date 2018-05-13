<template>
                <form method="POST" v-on:submit.prevent="submit()">

                    <div class="form-group">
                        <select v-bind:class="{ 'form-control': true, 'is-invalid': errors.spot_id }" v-model="spot">
                            <option v-for="spot in spots" v-bind:value="spot.id">
                                {{ spot.name }}
                            </option>
                        </select>
                        <div v-if="errors.spot_id" class="invalid-feedback">
                            {{ errors.spot_id[0] }}
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <input class="form-control" type="date" v-model="date">
                        </div>
                        <time-input-component label="Start" v-bind:time.sync="start_time"></time-input-component>
                        <time-input-component label="End" v-bind:time.sync="end_time"></time-input-component>
                    </div>

                    <div class="form-group">
                        <select v-bind:class="{ 'form-control': true, 'is-invalid': errors.swell_size }" v-model="swell_size">
                            <option v-for="size in swell_sizes" v-bind:value="size.value">
                                {{ size.description }}
                            </option>
                        </select>
                        <div v-if="errors.swell_size" class="invalid-feedback">
                            {{ errors.swell_size[0] }}
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <select v-bind:class="{ 'form-control': true, 'is-invalid': errors.wind_speed }" v-model="wind_speed">
                                <option v-for="speed in wind_speeds" v-bind:value="speed.value">
                                    {{ speed.description }}
                                </option>
                            </select>
                            <div v-if="errors.wind_speed" class="invalid-feedback">
                                {{ errors.wind_speed[0] }}
                            </div>
                        </div>

                        <div class="col">
                            <select v-bind:class="{ 'form-control': true, 'is-invalid': errors.wind_direction }" v-model="wind_direction">
                                <option v-for="direction in wind_directions" v-bind:value="direction.value">
                                    {{ direction.description }}
                                </option>
                            </select>
                            <div v-if="errors.wind_direction" class="invalid-feedback">
                                {{ errors.wind_direction[0] }}
                            </div>
                        </div>
                    </div>


                    <div class="group float-right">
                        <router-link to="/home" class="btn btn-link btn-md">Cancel</router-link>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
</template>

<script>
    Vue.component('time-input-component', require('./TimeInputComponent'));

    export default {
        data() {
            return {
                swell_sizes: [
                    {value: '', description: 'Wave Size'},
                    {value: 0, description: '0 ft - Flat'},
                    {value: 1, description: '1 ft - Ankle'},
                    {value: 2, description: '2 ft - Knee'},
                    {value: 3, description: '3 ft - Waist'},
                    {value: 4, description: '4 ft - Chest'},
                    {value: 5, description: '5 ft - Head'},
                    {value: 6, description: '6 ft - Overhead'},
                    {value: 8, description: '8 ft - 2ft Overhead'},
                    {value: 10, description: '10 ft - Double Overhead'}
                ],
                wind_speeds: [
                    {value: '', description: 'Wind Speed'},
                    {value: '0', description: 'Calm'},
                    {value: '2', description: 'Light'},
                    {value: '4', description: 'Moderate'},
                    {value: '6', description: 'Strong'},
                    {value: '8', description: 'Gale'},
                ],
                wind_directions: [
                    {value: '', description: 'Wind Direction'},
                    {value: 'offshore', description: 'Offshore'},
                    {value: 'cross-offshore', description: 'Cross Offshore'},
                    {value: 'cross-shore', description: 'Cross Shore'},
                    {value: 'onshore', description: 'Onshore'},
                ],
                spots: [],
                spot: '',
                date: (new Date()).toISOString().substr(0, 10), //TODO, there must be a better way to get the date only?
                start_time: null,
                end_time: null,
                swell_size: '', //TODO. better names for list and selected values?
                wind_speed: '',
                wind_direction: '',
                errors: []
            }
        },
        created() {
            axios.get("/api/v1/spot")
                .then(response => {
                    this.spots = response.data;
                });
        },
        methods: {
            submit() {
                var self = this;
                axios.post('/api/v1/surf', {
                    'spot_id': this.spot,
                    'date_start': new Date(this.date + ' ' + this.start_time).toISOString(),
                    'date_end': new Date(this.date + ' ' + this.end_time).toISOString(),
                    'swell_size': this.swell_size,
                    'wind_speed': this.wind_speed,
                    'wind_direction': this.wind_direction
                }).then(function (response) {
                    this.$router.push({name: 'home', params: {flash_message: 'Your surf session has been logged!'}});
                }).catch(function (error) {
                    if (error.response) {
                        self.errors = error.response.data;
                    }else{
                        //TODO, connection issue?
                    }
                });
            }
        }
    }
</script>