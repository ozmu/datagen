<template>
    <div id="highlightable">
        <div v-show="showTools" class="tools" :style="{left: `${x}px`, top: `${y}px`}" @mousedown.prevent="">
            <span class="item" v-for="entity in entities" :key="entity.id" :title="entity.entity" @mousedown.prevent="sendAction(entity.entity)">{{ entity.entity }}</span>
        </div>
        <slot/>
    </div>
</template>

<script>
export default {
    props: ["entities"],

    data () {
        return {
            x: 0,
            y: 0,
            showTools: false,
            selected: {
                text: '',
                selection: null
            }
        }
    },

    computed: {  
        highlightableEl(){
            return this.$slots.default[0].elm  
        }
    },

    mounted() {
        document.getElementById('highlightable').addEventListener('mouseup', this.onMouseup)
    }, 
    
    beforeDestroy() {
        document.getElementById('highlightable').removeEventListener('mouseup', this.onMouseup)
    },

    methods: {
        onMouseup() {
            const selection = window.getSelection()
            const startNode = selection.getRangeAt(0).startContainer.parentNode
            const endNode = selection.getRangeAt(0).endContainer.parentNode
            if (!startNode.isSameNode(this.highlightableEl) || !startNode.isSameNode(endNode)) {
                this.showTools = false
                return
            }
            const { x, y, width } = selection.getRangeAt(0).getBoundingClientRect()
            if (!width) {
                this.showTools = false
                return
            }
            this.x = x + (width / 2)
            this.y = y + window.scrollY - 10
            this.showTools = true
            this.selected.text = selection.toString()
            this.selected.selection = (document.all) ? document.selection.createRange().text : document.getSelection()
        },

        sendAction (type) {
            this.$emit('action', {type: type, value: this.selected.text, selection: this.selected.selection})
        }
    }
}   
</script>

<style scoped>
.tools {
  height: 30px;
  padding: 5px 10px;
  background: #333;
  border-radius: 3px;
  position: absolute;
  top: 0;
  left: 0;
  /*transform: translate(-50%, -100%);*/
  transform: translate(-152%,-350%);
  transition: 0.2s all;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1;
}
.tools:after {
  content: '';
  position: absolute;
  left: 50%;
  bottom: -5px;
  transform: translateX(-50%);
  width: 0;
  height: 0;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 6px solid #333;
}
.item {
  color: #fff;
  cursor: pointer;
}
.item path {
  fill: #fff;
}
.item:hover {
  color: #19f;
}
.item + .item {
  margin-left: 10px;
}
</style>