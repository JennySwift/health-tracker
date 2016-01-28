<button v-on:click="toggleQuickRecipeHelp()">Help</button>

<div v-show="showHelp" class="animate-show transition">
    <ul>
        <li>This is where you can quickly add a recipe by pasting it in the box below.</li>
        <li>It needs to be in a specific format for it to work,</li>
        <li>but if you get it wrong you should be told which line needs correcting.</li>
        <li>An example format is provided for you below, but here are the instructions:</li>
        <br>
        <li>For each ingredient, start a new line.</li>
        <li>On each ingredient line, enter the quantity, followed by the unit, then the food, and optionally, a description, separated by a comma.</li>
        <li>If you want to add steps for your recipe, put either of the following words on its own line, after the ingredients:</li>
        <li>Method, directions, or preparation.</li>
        <li>These words can be either lowercase or uppercase, and can be followed by a colon.</li>
        <li>After this, add each step on a new line.</li>
        <br>
    </ul>

    <p>If you enter a food or a unit for which a similar name is found, you will be prompted to choose whether to use your existing food/unit, or create the one you entered.
        For example, if you already have 'apple' in your foods, and you enter 'apples' into your recipe, you will be prompted.</p>

    <ul>
        <li>Example format:</li>
        <li>1 small apple, chopped</li>
        <li>2 large bananas</li>
        <li>Method:</li>
        <li>Blend</li>
        <li>Eat</li>
    </ul>
</div>