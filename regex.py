import re

import sys
topic = sys.argv[1]
key = sys.argv[2]

def regex(text, temp):
    word = text.lower();
    spam = temp.lower();
    reg = r"\b" + re.escape(spam) + r"\b"
    if re.search(reg, word, re.IGNORECASE):
        # print("Spam!")
        return True;
    else:
        # print("Safe!")
        return False;

#Twitter API + KMP
def spamDetect(topic,keyword):
    #Setup Twitter API
    from twython import Twython
    APP_KEY = 'pVqijAxfbDRTWBadqEHs43Ozm'
    APP_SECRET = '5n0b7DDeCYCqeiHxk2vDNR6gurMcj3mEBAJQ6xIlStShY3R0DR'
    OAUTH_TOKEN = '985071710100598784-NTA548fEqcop86mOBV2bbHUOaDfz3X4'
    OAUTH_TOKEN_SECRET = 'uByR9KjsKdqLL5c1rmcUCmC3G9MNesoWwEuZ1Zf5sskLv'

    twitter = Twython(APP_KEY, APP_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET)

    #From search
    result = twitter.search(q=topic, count=30, lang='id')
    all_tweets = result['statuses']

    num = 1
    for tweet in all_tweets:
        print("<br>")
        print(num,end="")
        print(".")
        print(tweet['created_at'])
        print("[@"+tweet['user']['screen_name']+"]")
        idx = regex(str(tweet['text'].lower()), keyword)
        tweet['text'] = Twython.html_for_tweet(tweet)
        print(tweet['text'].encode("utf-8"))
        if (idx):
            print('>>>SPAM<<<')
        num+=1

spamDetect(topic,key.lower())