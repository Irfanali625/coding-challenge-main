<template>
    <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">
        <input type="radio" class="btn-check" v-model="activeButton" :value="1" id="btnradio1" autocomplete="off" checked>
        <label class="btn btn-outline-primary" :class="{ 'active': activeButton === 1 }" for="btnradio1" @click.prevent="isSuggetionList">Suggestions ({{ count.commonConnections }})</label>

        <input type="radio" class="btn-check" v-model="activeButton" :value="2" id="btnradio2" autocomplete="off">
        <label class="btn btn-outline-primary" :class="{ 'active': activeButton === 2 }" for="btnradio2" @click.prevent="isSentRequestList">Sent Requests ({{ count.sentRequestsCount}})</label>

        <input type="radio" class="btn-check" v-model="activeButton" :value="3" id="btnradio3" autocomplete="off">
        <label class="btn btn-outline-primary" :class="{ 'active': activeButton === 3 }" for="btnradio3" @click.prevent="isRecentRequestList">Received Requests({{ count.recentRequestsCount}})</label>

        <input type="radio" class="btn-check" v-model="activeButton" :value="4" id="btnradio4" autocomplete="off">
        <label class="btn btn-outline-primary" :class="{ 'active': activeButton === 4 }" for="btnradio4" @click.prevent="isConnectionsList">Connections ({{ count.connectionsCount}})</label>
    </div>
<Seggestions v-if="isSuggetions"/>
<Connections v-if="isConnections"/>
<RecentRequests v-if="isRecentRequest"/>
<SentRequests v-if="isSentRequest"/>
</template>
<script setup>
import {onMounted, ref} from "vue";
import Seggestions from "./Components/Seggestions.vue";
import Connections from "./Components/Connections.vue";
import RecentRequests from "./Components/RecentRequests.vue";
import SentRequests from "./Components/SentRequests.vue";

const activeButton = ref(1);
const isSuggetions = ref(true);
const isConnections = ref(false);
const isRecentRequest = ref(false);
const isSentRequest = ref(false);
const count = ref([]);
function isSuggetionList(){
    isSuggetions.value = true;
    isConnections.value = false;
    isRecentRequest.value = false;
    isSentRequest.value = false;
    activeButton.value = 1;
}
function isSentRequestList(){
    isSentRequest.value = true;
    isRecentRequest.value = false;
    isConnections.value = false;
    isSuggetions.value = false;
    activeButton.value = 2;
}
function isRecentRequestList(){
    isRecentRequest.value = true;
    isConnections.value = false;
    isSuggetions.value = false;
    isSentRequest.value = false;
    activeButton.value = 3;
}
function isConnectionsList(){
    isConnections.value = true;
    isSuggetions.value = false;
    isRecentRequest.value = false;
    isSentRequest.value = false;
    activeButton.value = 4;
}

function getConntionsCount(){
        axios.get('get-connections-count')
            .then((res) => {
                console.log(res.data);
                count.value = res.data.count;
            })
            .catch((error) => {
                console.error(error);
            });
}
onMounted(() =>{
    getConntionsCount();
})

</script>
