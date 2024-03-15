const dayInput = document.getElementById('remind_me_on');
const timeInput = document.getElementById('remind_me_at');
const durationInput = document.getElementById('duration');
const span = document.getElementById('example_time');

const MONTHS = ['jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'];

const pz = (i) => i > 9 && i || `0${i}`;
const dateToTime = (d) => `${pz(d.getHours())}:${pz(d.getMinutes())}`;

[dayInput, timeInput, durationInput].forEach((input) => {
    input.addEventListener('input', () => {
        if (durationInput.value < 10 || durationInput.value > 240 || durationInput.value % 5 !== 0) {
            return;
        }

        const start = new Date(new Date().toDateString() + ' ' + timeInput.value);

        if (isNaN(start)) {
            return;
        }

        const end = new Date(start);
        end.setMinutes(start.getMinutes() + durationInput.value);

        if (dayInput.value === 'before') {
            start.setDate(start.getDate() - 1);
        }

        span.textContent = `${start.getDate()} ${MONTHS[start.getMonth()]}. ${start.getFullYear()} ${dateToTime(start)}`;
        span.textContent += ` - ${dateToTime(end)}`;
    });
})
