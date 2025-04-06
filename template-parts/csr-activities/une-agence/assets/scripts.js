function UneAgence() {
    // Tạo các vòng lặp cho cột lẻ và cột chẵn
    let oddColumns = document.querySelectorAll(".col1 .enu-agence-section_galler-col_img, .col3 .enu-agence-section_galler-col_img");
    let evenColumns = document.querySelectorAll(".col2 .enu-agence-section_galler-col_img, .col4 .enu-agence-section_galler-col_img");

    let loopOdd = verticalLoop(oddColumns, {
        speed: 1,
        repeat: -1,
        paddingBottom: 25,
        reversed: true // Bắt đầu chuyển động lên trên
    });

    let loopEven = verticalLoop(evenColumns, {
        speed: 1,
        repeat: -1,
        paddingBottom: 25,
        reversed: false // Bắt đầu chuyển động xuống dưới
    });

    // Hàm đặt hướng và tốc độ
    function setDirection(direction, speedMultiplier = 1) {
        gsap.to(loopOdd, { 
            timeScale: direction * speedMultiplier, 
            duration: 0.3, 
            overwrite: true 
        });
        loopOdd.direction = direction;

        gsap.to(loopEven, { 
            timeScale: -direction * speedMultiplier, 
            duration: 0.3, 
            overwrite: true 
        });
        loopEven.direction = -direction;
    }

    // Observer để theo dõi cuộn
    Observer.create({
        target: window,
        type: "wheel,scroll,touch",
        onDown: () => {
            console.log("onDown: speed 5x");
            setDirection(1, 5); // Odd xuống, Even lên, tốc độ 5x
        },
        onUp: () => {
            console.log("onUp: speed 3x");
            setDirection(-1, 3); // Odd lên, Even xuống, tốc độ 3x
        },
        onStop: () => {
            console.log("stop: speed 1x");
            gsap.to(loopOdd, {
                timeScale: loopOdd.direction * 1, // Dùng direction hiện tại của loopOdd
                duration: 0.3,
                overwrite: true
            });
            gsap.to(loopEven, {
                timeScale: loopEven.direction * 1, // Dùng direction hiện tại của loopEven
                duration: 0.3,
                overwrite: true
            });
        }
    });

    // Hàm verticalLoop (giữ nguyên)
    function verticalLoop(items, config) {
        items = gsap.utils.toArray(items);
        config = config || {};
        let tl = gsap.timeline({
                repeat: config.repeat,
                paused: config.paused,
                defaults: { ease: "none" },
                onReverseComplete: () =>
                    tl.totalTime(tl.rawTime() + tl.duration() * 100),
            }),
            length = items.length,
            startY = items[0].offsetTop,
            times = [],
            heights = [],
            yPercents = [],
            curIndex = 0,
            pixelsPerSecond = (config.speed || 1) * 100,
            snap =
                config.snap === false ? (v) => v : gsap.utils.snap(config.snap || 1),
            totalHeight,
            curY,
            distanceToStart,
            distanceToLoop,
            item,
            i;

        gsap.set(items, {
            yPercent: (i, el) => {
                let h = (heights[i] = parseFloat(gsap.getProperty(el, "height", "px")));
                yPercents[i] = snap(
                    (parseFloat(gsap.getProperty(el, "y", "px")) / h) * 100 +
                        gsap.getProperty(el, "yPercent")
                );
                return yPercents[i];
            },
        });
        gsap.set(items, { y: 0 });

        totalHeight =
            items[length - 1].offsetTop +
            (yPercents[length - 1] / 100) * heights[length - 1] -
            startY +
            items[length - 1].offsetHeight *
                gsap.getProperty(items[length - 1], "scaleY") +
            (parseFloat(config.paddingBottom) || 0);

        for (i = 0; i < length; i++) {
            item = items[i];
            curY = (yPercents[i] / 100) * heights[i];
            distanceToStart = item.offsetTop + curY - startY;
            distanceToLoop =
                distanceToStart + heights[i] * gsap.getProperty(item, "scaleY");

            tl.to(
                item,
                {
                    yPercent: snap(((curY - distanceToLoop) / heights[i]) * 100),
                    duration: distanceToLoop / pixelsPerSecond,
                },
                0
            )
            .fromTo(
                item,
                {
                    yPercent: snap(
                        ((curY - distanceToLoop + totalHeight) / heights[i]) * 100
                    ),
                },
                {
                    yPercent: yPercents[i],
                    duration:
                        (curY - distanceToLoop + totalHeight - curY) / pixelsPerSecond,
                    immediateRender: false,
                },
                distanceToLoop / pixelsPerSecond
            )
            .add("label" + i, distanceToStart / pixelsPerSecond);

            times[i] = distanceToStart / pixelsPerSecond;
        }

        function toIndex(index, vars) {
            vars = vars || {};
            Math.abs(index - curIndex) > length / 2 &&
                (index += index > curIndex ? -length : length);
            let newIndex = gsap.utils.wrap(0, length, index),
                time = times[newIndex];

            if (time > tl.time() !== index > curIndex) {
                vars.modifiers = { time: gsap.utils.wrap(0, tl.duration()) };
                time += tl.duration() * (index > curIndex ? 1 : -1);
            }

            curIndex = newIndex;
            vars.overwrite = true;
            return tl.tweenTo(time, vars);
        }

        tl.next = (vars) => toIndex(curIndex + 1, vars);
        tl.previous = (vars) => toIndex(curIndex - 1, vars);
        tl.current = () => curIndex;
        tl.toIndex = (index, vars) => toIndex(index, vars);
        tl.times = times;
        tl.progress(1, true).progress(0, true);

        if (config.reversed) {
            tl.vars.onReverseComplete();
            tl.reverse();
        }

        tl.direction = config.reversed ? -1 : 1; // Thuộc tính hướng
        return tl;
    }
}

export default UneAgence;