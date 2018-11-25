<template>
    <tbody>
        <tr class="match-summary table-info">
            <td nowrap="nowrap"><strong>{{ spots[spot_id].name }}</strong></td>
            <td>{{ getForecast(match).swell_minBreakingHeight }}-{{ getForecast(match).swell_maxBreakingHeight }}ft</td>
            <td nowrap="nowrap">
                <img src="http://cdnimages.magicseaweed.com/star_filled.png" v-for="star in new Array(getForecast(match).solidRating)" />
                <img src="http://cdnimages.magicseaweed.com/star_empty.png" v-for="star in new Array(getForecast(match).fadedRating)" />
            </td>
            <td nowrap="nowrap" class="text-center">
                <span :class="getForecastSwellDirectionClasses(getForecast(match))" :title="Math.round(this.getForecast(match).swell_primary_trueDirection)"></span><br />
                {{ getForecast(match).swell_primary_height }}ft @ {{ getForecast(match).swell_primary_period }}s
            </td>
            <td nowrap="nowrap" class="text-center">
                <span :class="getForecastWindDirection(getForecast(match))" :title="Math.round(this.getForecast(match).wind_trueDirection)"></span><br />
                {{ getForecast(match).wind_speed }} mph
            </td>
            <td>{{ match.averages.swell_size }}ft</td>
            <td>{{ windSpeed(match.averages.wind_speed) }}</td>
            <td>{{ Math.round(match.averages.average_match, 0) }}%</td>
            <td>{{ Object.keys(match.surfs).length }}</td>
            <td></td>
        </tr>

        <tr v-for="(surf, surf_id) in this.match.surfs">
            <td nowrap="nowrap"><strong>{{ surfs[surf_id].spot.name }}</strong></td>
            <td>{{ surfs[surf_id].msw_forecast.swell_minBreakingHeight }}-{{ surfs[surf_id].msw_forecast.swell_maxBreakingHeight }}ft</td>
            <td nowrap="nowrap">
                <img src="http://cdnimages.magicseaweed.com/star_filled.png" v-for="star in new Array(surfs[surf_id].msw_forecast.solidRating)" />
                <img src="http://cdnimages.magicseaweed.com/star_empty.png" v-for="star in new Array(surfs[surf_id].msw_forecast.fadedRating)" />
            </td>
            <td nowrap="nowrap" class="text-center">
                <span :class="getForecastSwellDirectionClasses(surfs[surf_id].msw_forecast)" :title="Math.round(surfs[surf_id].msw_forecast.swell_primary_trueDirection)"></span><br />
                {{ surfs[surf_id].msw_forecast.swell_primary_height }}ft @ {{ surfs[surf_id].msw_forecast.swell_primary_period }}s
            </td>
            <td nowrap="nowrap" class="text-center">
                <span :class="getForecastWindDirection(surfs[surf_id].msw_forecast)" :title="Math.round(surfs[surf_id].msw_forecast.wind_trueDirection)"></span><br />
                {{ surfs[surf_id].msw_forecast.wind_speed }} mph
            </td>
            <td>{{ surfs[surf_id].swell_size }}ft</td>
            <td>{{ windSpeed(surfs[surf_id].wind_speed) }}</td>
            <td>{{ (new Date(surfs[surf_id].date_start * 1000)).toLocaleString() }}</td>
            <td>{{ (new Date(surfs[surf_id].date_end * 1000)).toLocaleString() }}</td>
            <td v-html="photoLink(surfs[surf_id])"></td>
        </tr>
    </tbody>
</template>

<script>
    export default {
        props: [
            'match',
            'spot_id',
            'spots',
            'forecasts',
            'surfs',
        ],
        methods: {
            getForecast(match) {
                return this.forecasts[match.forecast_id];
            },
            getForecastSwellDirectionClasses(forecast) {
                let classes = {'msw-swa': true};
                let dir = Math.round(forecast.swell_primary_trueDirection / 5) * 5;
                classes['msw-swa-' + dir] = true;
                return classes;
            },
            getForecastWindDirection(forecast) {
                let classes = {'msw-ssa': true};
                let dir = Math.round(forecast.wind_trueDirection / 5) * 5;
                classes['msw-ssa-' + dir] = true;
                return classes;
            },
            photoLink(surf) {
                if (surf.photos.length) {
                    let url = '/photo/' + surf.photos[0].id;
                    return '<a target="_blank" href="'+url+'">Photo</a>'
                }

                return '';
            }
        },

    }
</script>