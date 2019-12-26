<template>
    <div>
        <div class="alert alert-info" v-show="flash_message">
            {{ flash_message }}
        </div>

        <div v-if="errors.generic" class="alert alert-danger">
            {{ errors.generic }}
        </div>

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
                <small class="form-text text-muted float-right">
                    <router-link :to="{name: 'create-spot'}">Create New Spot</router-link>
                </small>
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
                        <option v-for="speed in selectableWindSpeeds()" v-bind:value="speed.value">
                            {{ speed.name }}
                        </option>
                    </select>
                    <div v-if="errors.wind_speed" class="invalid-feedback">
                        {{ errors.wind_speed[0] }}
                    </div>
                </div>

                <div class="col">
                    <select v-bind:class="{ 'form-control': true, 'is-invalid': errors.wind_direction }" v-model="wind_direction">
                        <option v-for="(description, direction) in windDirections" v-bind:value="direction">
                            {{ description }}
                        </option>
                    </select>
                    <div v-if="errors.wind_direction" class="invalid-feedback">
                        {{ errors.wind_direction[0] }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="photo" @change="showUploadPreview($event)">
                    <label class="custom-file-label" for="photo">Upload a Photo</label>
                </div>
            </div>


            <div class="form-row">
                <div class="col">
                    <img id="photo-preview" src="">
                </div>
                <div class="col text-right">
                    <router-link :to="{name: 'home'}" class="btn btn-link btn-md">Cancel</router-link>
                    <button type="submit" class="btn btn-primary" :disabled="submitting">{{ submitButtonText }}</button>
                </div>
            </div>

        </form>
    </div>
</template>

<script>
    Vue.component('time-input-component', require('./TimeInputComponent'));

    export default {
        props: ['flash_message', 'selected_spot_id'],
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
                spots: [],
                spot: '',
                date: (new Date()).toISOString().substr(0, 10), //TODO, there must be a better way to get the date only?
                start_time: null,
                end_time: null,
                swell_size: '', //TODO. better names for list and selected values?
                wind_speed: '',
                wind_direction: 0,
                submitting: false,
                errors: []
            }
        },
        computed: {
            submitButtonText() {
                return this.submitting ? 'Uploading...' : 'Create Surf';
            }
        },
        created() {
            axios.get("/api/v1/spot")
                .then(response => {
                    if (response.data.length) {
                        this.spots = response.data;
                        this.spot = this.selected_spot_id;
                    }else{
                        this.$router.push({name: 'create-spot'});
                    }
                });
        },
        methods: {
            showUploadPreview(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $('#photo-preview').attr('src', e.target.result);

                        const preview = $('#photo-preview')[0];
                        EXIF.getData(preview, function() {
                            switch (EXIF.getTag(this, 'Orientation')) {
                                case 3: $(preview).css('transform', 'rotate(180deg)'); break;
                                case 6: $(preview).css('transform', 'rotate(90deg)'); break;
                                case 8: $(preview).css('transform', 'rotate(270deg)'); break;
                            }
                        });
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            },
            submit() {
                this.submitting = true;
                var self = this;
                var formData = new FormData();
                formData.append('spot_id', this.spot);
                formData.append('date_start', new Date(this.date + ' ' + this.start_time).toISOString(),);
                formData.append('date_end', new Date(this.date + ' ' + this.end_time).toISOString());
                formData.append('swell_size', this.swell_size);
                formData.append('wind_speed', this.wind_speed);
                formData.append('wind_direction', this.wind_direction);
                formData.append('photo', document.getElementById('photo').files.length
                    ? document.getElementById('photo').files[0]
                    : '');

                axios.post('/api/v1/surf', formData).then(function (response) {
                    self.$router.push({name: 'home', params: {flash_message: 'Your surf session has been logged!'}});
                }).catch(function (error) {
                    if (error.response) {
                        self.errors = error.response.data;
                    }else{
                        self.errors = {generic: 'There was a error trying to submit the form. Are you still connected to the internet?'}
                    }
                    self.submitting = false;
                });
            }
        }
    }
</script>
