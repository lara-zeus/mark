export default function zeusMark({state, icons, isSequential}) {

    return {
        state,
        icons,
        isSequential,
        isSelected(state) {
            if(! this.isSequential) {
                return state === this.state
            }

            const stateIndex = this.icons.indexOf(this.state)

            const selectedIcons = this.icons.slice(0, stateIndex + 1)

            return selectedIcons.includes(state)
        }
    }
}
