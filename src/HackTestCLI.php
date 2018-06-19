<?hh // strict
/*
 *  Copyright (c) 2018-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\HackTest;

use type Facebook\CLILib\CLIWithRequiredArguments;
use namespace Facebook\CLILib\CLIOptions;
use namespace HH\Lib\{C, Str};

/** The main `hacktest` CLI */
final class HackTestCLI extends CLIWithRequiredArguments {

  private bool $verbose = false;

  <<__Override>>
  public static function getHelpTextForRequiredArguments(): vec<string> {
    return vec['SOURCE_PATH'];
  }

  <<__Override>>
  protected function getSupportedOptions(): vec<CLIOptions\CLIOption> {
    return vec[
      CLIOptions\flag(
        () ==> {
          $this->verbose = true;
        },
        "Increase output verbosity",
        '--verbose',
        '-v',
      ),
    ];
  }

  <<__Override>>
  public async function mainAsync(): Awaitable<int> {
    $this->getStdout()
      ->write("HackTest 1.0 by Wilson Lin and contributors.\n\n");
    $output = await HackTestRunner::runAsync($this->getArguments(), $this->verbose);
    $output_chunks = Str\chunk($output, 1024);
    foreach ($output_chunks as $chunk) {
      $this->getStdout()->write($chunk);
    }
    return 0;
  }
}
