<?php

return <<<'VALUE'
"namespace IPS\\Theme;\n\tfunction email_html_core_postBeforeRegisterFollowup( $allContent, $secret, $email ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\n{$email->language->addToStack(\"post_before_register_followup_text\", FALSE)}\n<br \/>\n<br \/>\n<a href='\nCONTENT;\n\n$return .= htmlspecialchars( \\IPS\\Http\\Url::internal( \"app=core&module=system&controller=register&pbr={$secret}&hidereminder=1\", \"front\", \"register\", array(), 0 ), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', TRUE );\n$return .= <<<CONTENT\n' style=\"color: #ffffff; font-family: 'Helvetica Neue', helvetica, sans-serif; text-decoration: none; font-size: 14px; background: \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->email_color;\n$return .= <<<CONTENT\n; line-height: 32px; padding: 0 10px; display: inline-block; border-radius: 3px;\">{$email->language->addToStack(\"finish_this_submission\", FALSE)}<\/a>\n\n\n\n<br \/><br \/>\n<em style='color: #8c8c8c'>&mdash; \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->board_name;\n$return .= <<<CONTENT\n<\/em>\nCONTENT;\n\n\t\treturn $return;\n}\n\tfunction email_plaintext_core_postBeforeRegisterFollowup( $allContent, $secret, $email ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n{$email->language->addToStack(\"post_before_register_followup_text\")}\n\n{$email->language->addToStack(\"finish_this_submission\", FALSE)}: \nCONTENT;\n\n$return .= \\IPS\\Http\\Url::internal( \"app=core&module=system&controller=register&pbr={$secret}&hidereminder=1\", \"front\", \"register\", array(), 0 );\n$return .= <<<CONTENT\n\nCONTENT;\n\n\t\treturn $return;\n}"
VALUE;
