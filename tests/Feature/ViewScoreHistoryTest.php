<?php

namespace Tests\Feature;

use App\Bowling;
use Tests\TestCase;

class ViewScoreHistoryTest extends TestCase
{
    /**
     * @var array
     */
    protected $inputFrames;

    /**
     * @var Bowling
     */
    protected $bowling;

    public function setUp()
    {
        parent::setUp();
        $this->bowling = new Bowling();
        $this->setInputFramesDefault();
    }

    public function test_all_entries_are_0_for_gutter_game()
    {
        $frames = array_fill(0, Bowling::FRAMES_PER_GAME, [0, 0]);

        $this->bowling->setInputFrames($frames);

        $expectedArray = array_fill(0, Bowling::FRAMES_PER_GAME, 0);

        $this->assertEquals($expectedArray, $this->bowling->getScoreHistory());
    }

    public function test_correct_when_no_frames_has_strike_nor_spare()
    {
        $frames = [];
        $expectedScoreHistory = [];
        $prevFrameScore = 0;

        for ($i = 0; $i < Bowling::FRAMES_PER_GAME; $i++) {
            $firstRoll = rand(0, 9);
            $secondRoll = 9 - $firstRoll;

            $frames[] = [$firstRoll, $secondRoll];
            $expectedScoreHistory[] = 9 + $prevFrameScore;
            $prevFrameScore = $expectedScoreHistory[$i];
        }

        $this->bowling->setInputFrames($frames);


        $this->assertEquals($expectedScoreHistory, $this->bowling->getScoreHistory());
    }

    public function test_correct_with_question_input_and_expected_output()
    {
        $this->bowling->setInputFrames($this->inputFrames);

        $expectedOutput = [7, 16, 26, 41, 46, 54, 63, 71, 78, 96];
        $this->assertEquals($expectedOutput, $this->bowling->getScoreHistory());
    }

    public function test_final_score_is_300_in_a_perfect_game()
    {
        $frames = array_fill(0, Bowling::FRAMES_PER_GAME - 1, [10]);

        // The final frame
        $frames[] = [10, 10, 10];

        $this->bowling->setInputFrames($frames);

        $expectedScoreHistory = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300];
        $actualScoreHistory = $this->bowling->getScoreHistory();

        $this->assertEquals($expectedScoreHistory, $actualScoreHistory);
    }

    public function test_final_score_is_20_when_all_i_rolled_is_1()
    {
        $frames = array_fill(0, Bowling::FRAMES_PER_GAME, [1, 1]);


        $this->bowling->setInputFrames($frames);

        $expectedScoreHistory = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20];
        $actualScoreHistory = $this->bowling->getScoreHistory();

        $this->assertEquals($expectedScoreHistory, $actualScoreHistory);
    }

}
