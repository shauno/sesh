<template>
    <tr class="match-summary table-info">
        <td nowrap="nowrap"><strong>{{ spots[spot_id].name }}</strong></td>
        <td>{{ getForecast(match).swell_minBreakingHeight }}-{{ getForecast(match).swell_maxBreakingHeight }}ft</td>
        <td nowrap="nowrap">
            <img src="http://cdnimages.magicseaweed.com/star_filled.png" v-for="star in new Array(getForecast(match).solidRating)" />
            <img src="http://cdnimages.magicseaweed.com/star_empty.png" v-for="star in new Array(getForecast(match).fadedRating)" />
        </td>
        <td nowrap="nowrap" class="text-center">
            <span :class="getForecastSwellDirection(match)"></span><br />
            {{ getForecast(match).swell_primary_height }}ft @ {{ getForecast(match).swell_primary_period }}s
        </td>
        <td nowrap="nowrap" class="text-center">
            <span :class="getForecastWindDirection(match)"></span><br />
            {{ getForecast(match).wind_speed }} mph
        </td>
        <td>{{ match.averages.swell_size }}ft</td>
        <td>{{ windSpeed(match.averages.wind_speed) }}</td>
        <td>{{ Math.round(match.averages.average_match, 0) }}%</td>
        <td>{{ Object.keys(match.surfs).length }}</td>
    </tr>
</template>

<script>
    export default {
        props: [
            'match',
            'spot_id',
            'spots',
            'forecasts',
        ],
        methods: {
            getForecast(match) {
                return this.forecasts[match.forecast_id];
            },
            getForecastSwellDirection(match) {
                let classes = {'msw-swa': true};
                classes['msw-swa-' + Math.round(this.getForecast(match).swell_primary_direction / 5) * 5] = true;
                return classes;
            },
            getForecastWindDirection(match) {
                let classes = {'msw-ssa': true};
                classes['msw-ssa-' + Math.round(this.getForecast(match).wind_direction / 5) * 5] = true;
                return classes;
            }
        },
    }
</script>