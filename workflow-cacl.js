const workflows = [
    // { cost: 100, frequency: 0.9 }, // PHP Tests?
    { cost: 90, frequency: 0.9 }, // JS Tests?
    { cost: 30, frequency: 0.5 }, // Other Tests?
    { cost: 30, frequency: 0.3 }, // Build
    { cost: 200, frequency: 0.1 }, // VR Tests?
];

const run = () => { 
    let currentCost = 0;
    let optimisedCost = 0;
    let runnerCost = 0;

    workflows.map( workflow => currentCost += workflow.cost );
    workflows.map( workflow => { 
            optimisedCost += Math.random() < workflow.frequency ? workflow.cost : 0;
            runnerCost = workflow.cost > runnerCost ? workflow.cost : runnerCost;
    } )
    optimisedCost += runnerCost;
    return currentCost > optimisedCost;
}

const runCount = 10000;
let optimisedWinsCount = 0;

for(let i = 0; i <= runCount; i++) {
    if ( run() ) {
        optimisedWinsCount++;
    }
}

console.log( `Optimised wins: ${Math.round(optimisedWinsCount / runCount * 100)}%` );