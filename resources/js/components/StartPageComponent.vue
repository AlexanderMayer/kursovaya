<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'

let lots = ref('');

const data = async () => {
    try {
        const response = await axios.get(
            'api/main'
        ).then( res => {
            lots.value = res.data;
        })
    } catch (error) {
        console.error('Ошибка вывода',error);
        throw error;
    }
}

onMounted(() => {
    data();
    }
)
</script>

<template>
    <div>Здест будут отображаться лоты стартовой страницы</div>
    <div class="container" v-show="lots">
        <div class="row" v-for="lot in lots">
            <div class="col">
                {{}}
            </div>
            <div class="col">
                {{lot.title}}
            </div>
            <div class="col">
                {{lot.description}}
            </div>
            <div class="col">
                Продавец: {{}}
            </div>
            <div class="col">
                {{}}
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
